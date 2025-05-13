<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Config;

use JsonException;
use Jtl\Connector\Core\Config\FileConfig;
use Jtl\Connector\Core\Exception\ConfigException;
use Jtl\Connector\Core\Test\TestCase;
use Noodlehaus\Exception\EmptyDirectoryException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class FileConfig
 *
 * @package Jtl\Connector\Core\Test\Config
 */
class FileConfigTest extends TestCase
{
    /**
     * @return void
     * @throws ConfigException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testConfigSetParameter(): void
    {
        $fileConfig = $this->createFileConfig();

        $testKey   = 'test';
        $testValue = \mt_rand();

        $fileConfig->set($testKey, $testValue);

        $this->assertNotNull($fileConfig->get($testKey, null));
        $this->assertSame($testValue, $fileConfig->get($testKey));
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws EmptyDirectoryException
     */
    public function testConfigSetParameterCannotBeEmpty(): void
    {
        $this->expectException(ConfigException::class);

        $fileConfig = $this->createFileConfig();

        $fileConfig->set('', '');
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws EmptyDirectoryException
     */
    public function testSave(): void
    {
        $file        = $this->createConfigFile();
        $fileContent = \file_get_contents($file);
        $this->assertNotFalse($fileContent);
        $data = \json_decode($fileContent, true, 512, \JSON_THROW_ON_ERROR);
        $this->assertIsArray($data);
        $this->assertArrayNotHasKey('yo', $data);
        $this->assertArrayNotHasKey('foo', $data);
        $config = new FileConfig($file);
        $config->set('yo', 'lo');
        $config->set('foo', 'bar');
        $config->write();
        $fileContent = \file_get_contents($file);
        $this->assertNotFalse($fileContent);
        $data = \json_decode($fileContent, true, 512, \JSON_THROW_ON_ERROR);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('yo', $data);
        $this->assertEquals('lo', $data['yo']);
        $this->assertArrayHasKey('foo', $data);
        $this->assertEquals('bar', $data['foo']);
    }
}
