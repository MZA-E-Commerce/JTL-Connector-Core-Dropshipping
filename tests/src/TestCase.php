<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test;

use Faker\Factory;
use Faker\Generator;
use Jtl\Connector\Core\Config\ArrayConfig;
use Jtl\Connector\Core\Config\FileConfig;
use Jtl\Connector\Core\Model\Identity;
use Noodlehaus\Exception\EmptyDirectoryException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamException;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class TestCase
 *
 * @package Jtl\Connector\Core\Tests
 */
class TestCase extends \Jtl\Connector\MappingTables\TestCase
{
    protected string            $connectorDir = \TEST_DIR;
    protected ?string           $configFile   = null;
    private ?Generator          $faker        = null;
    private ?vfsStreamDirectory $rootDir      = null;

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $files = [$this->configFile,];

        foreach ($files as $file) {
            if (!\is_null($file) && \is_file($file)) {
                \unlink($file);
            }
        }

        $dirs = [
            \sprintf('%s/plugins', $this->connectorDir),
            \sprintf('%s/db', $this->connectorDir),
            \sprintf('%s/var', $this->connectorDir),
        ];

        foreach ($dirs as $dir) {
            if (\is_dir($dir)) {
                $this->removeDirRecursive($dir);
            }
        }
    }

    /**
     * @param string $dirname
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    protected function removeDirRecursive(string $dirname): void
    {
        $elements = \glob($dirname . '/*');
        $this->assertIsNotBool($elements);
        foreach ($elements as $element) {
            \is_dir($element) ? $this->removeDirRecursive($element) : \unlink($element);
        }
        \rmdir($dirname);
    }

    /**
     * @return Identity
     * @throws \Exception
     */
    protected function createIdentity(): Identity
    {
        return new Identity($this->createEndpointId(), $this->createHostId());
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function createEndpointId(): string
    {
        return \sprintf('%s_%s', 't', $this->createHostId());
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function createHostId(): int
    {
        return \random_int(1, 9999);
    }

    /**
     * @param class-string $className
     *
     * @return array<int, array<int, mixed>>
     * @throws \ReflectionException
     */
    protected function getCorrectConstantsTestCases(string $className): array
    {
        $reflection = new \ReflectionClass($className);
        $constants  = \array_values($reflection->getConstants());

        $testCases = [];
        foreach ($constants as $constant) {
            $testCases[] = [
                $constant,
                true,
            ];
        }

        return $testCases;
    }

    /**
     * @param array<mixed> $data
     *
     * @return ArrayConfig
     */
    protected function createConfig(array $data = []): ArrayConfig
    {
        return new ArrayConfig($data);
    }

    /**
     * @param string $payload
     * @param string $extension
     *
     * @return FileConfig
     * @throws EmptyDirectoryException
     */
    protected function createFileConfig(string $payload = '{}', string $extension = 'json'): FileConfig
    {
        return new FileConfig($this->createConfigFile($payload, $extension));
    }

    /**
     * @param string $payload
     * @param string $extension
     *
     * @return string
     */
    protected function createConfigFile(string $payload = '{}', string $extension = 'json'): string
    {
        $this->configFile = \sprintf('%s/%s.%s', \sys_get_temp_dir(), \uniqid('connector-config', true), $extension);
        \file_put_contents($this->configFile, $payload);
        return $this->configFile;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws vfsStreamException
     */
    protected function createFile(): string
    {
        return $this->createFiles(1)[0];
    }

    /**
     * @param int $quantity
     *
     * @return array<int, string>
     * @throws \InvalidArgumentException
     * @throws vfsStreamException
     */
    protected function createFiles(int $quantity = 2): array
    {
        $files = [];

        for ($i = 0; $i < $quantity; $i++) {
            $file = vfsStream::newFile(\time() . $i . '-file');

            $this->getRootDir()->addChild($file);

            $files[] = vfsStream::url($file->path());
        }

        return $files;
    }

    /**
     * @return vfsStreamDirectory
     * @throws \InvalidArgumentException
     * @throws vfsStreamException
     */
    protected function getRootDir(): vfsStreamDirectory
    {
        if (\is_null($this->rootDir)) {
            $this->rootDir = vfsStream::setup();
        }
        return $this->rootDir;
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $_POST = [];
    }

    /**
     * @return Generator
     * @throws \InvalidArgumentException
     */
    protected function getFaker(): Generator
    {
        if (\is_null($this->faker)) {
            $this->faker = Factory::create();
        }
        return $this->faker;
    }
}
