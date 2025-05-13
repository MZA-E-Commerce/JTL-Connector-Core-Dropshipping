<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Config;

use JsonException;
use Jtl\Connector\Core\Config\ConfigParameter;
use Jtl\Connector\Core\Config\ConfigSchema;
use Jtl\Connector\Core\Exception\ConfigException;
use Jtl\Connector\Core\Test\TestCase;
use Noodlehaus\Exception\EmptyDirectoryException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class ConfigSchemaTest extends TestCase
{
    /**
     * @return void
     * @throws ConfigException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetOption(): void
    {
        $schema = (new ConfigSchema())->setParameters(...ConfigSchema::createDefaultParameters($this->connectorDir));
        $this->assertInstanceOf(ConfigParameter::class, $schema->getParameter(ConfigSchema::LOG_LEVEL));
    }

    /**
     * @return void
     * @throws ConfigException
     */
    public function testGetNotExistingParameter(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::UNKNOWN_PARAMETER);
        (new ConfigSchema())->getParameter('foobar');
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsParameter(): void
    {
        $schema = (new ConfigSchema())->setParameters(...ConfigSchema::createDefaultParameters($this->connectorDir));
        $this->assertTrue($schema->hasParameter(ConfigSchema::LOG_LEVEL));
        $this->assertFalse($schema->hasParameter('yolo'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetParameters(): void
    {
        $schema = new ConfigSchema();
        $schema->setParameter(new ConfigParameter('foo', ConfigParameter::TYPE_INTEGER));
        $reflectionClass    = new \ReflectionClass($schema);
        $reflectionProperty = $reflectionClass->getProperty('parameters');
        $reflectionProperty->setAccessible(true);
        $schemaArray = $reflectionProperty->getValue($schema);
        $this->assertIsArray($schemaArray);
        $this->assertEquals(\array_values($schemaArray), $schema->getParameters());
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSetparameter(): void
    {
        $schema             = new ConfigSchema();
        $reflectionClass    = new \ReflectionClass($schema);
        $reflectionProperty = $reflectionClass->getProperty('parameters');
        $reflectionProperty->setAccessible(true);
        $reflectSchema = $reflectionProperty->getValue($schema);
        $this->assertIsArray($reflectSchema);
        $this->assertCount(0, $reflectSchema);
        $option = new ConfigParameter('foo', ConfigParameter::TYPE_INTEGER);
        $schema->setParameter($option);
        $reflectSchema = $reflectionProperty->getValue($schema);
        $this->assertIsArray($reflectSchema);
        $this->assertCount(1, $reflectSchema);
        $actualOption = $schema->getParameter('foo');
        $this->assertEquals($option, $actualOption);
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSetParameters(): void
    {
        $schema             = new ConfigSchema();
        $reflectionClass    = new \ReflectionClass($schema);
        $reflectionProperty = $reflectionClass->getProperty('parameters');
        $reflectionProperty->setAccessible(true);
        $reflectSchema = $reflectionProperty->getValue($schema);
        $this->assertIsArray($reflectSchema);
        $this->assertCount(0, $reflectSchema);
        $defaultParameters = ConfigSchema::createDefaultParameters($this->connectorDir);
        $schema->setParameters(...$defaultParameters);
        $reflectSchema = $reflectionProperty->getValue($schema);
        $this->assertIsArray($reflectSchema);
        $this->assertCount(\count($defaultParameters), $reflectSchema);
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetDefaultValues(): void
    {
        $schema   = (new ConfigSchema())->setParameters(...ConfigSchema::createDefaultParameters($this->connectorDir));
        $expected = [
            ConfigSchema::LOG_LEVEL               => 'info',
            ConfigSchema::LOG_FORMAT              => 'line',
            ConfigSchema::MAIN_LANGUAGE           => 'de',
            ConfigSchema::CONNECTOR_DIR           => $this->connectorDir,
            ConfigSchema::LOG_DIR                 => \sprintf('%s/var/log', $this->connectorDir),
            ConfigSchema::CACHE_DIR               => \sprintf('%s/var/cache', $this->connectorDir),
            ConfigSchema::PLUGINS_DIR             => \sprintf('%s/plugins', $this->connectorDir),
            ConfigSchema::FEATURES_PATH           => \sprintf('%s/config/features.json', $this->connectorDir),
            ConfigSchema::DEBUG                   => false,
            ConfigSchema::SERIALIZER_ENABLE_CACHE => true,
        ];
        $actual   = $schema->getDefaultValues();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function testValidate(): void
    {
        $options = [
            ConfigParameter::create('foo', ConfigParameter::TYPE_STRING, true),
            ConfigParameter::create('nothing', ConfigParameter::TYPE_STRING, false),
            ConfigParameter::create('yo', ConfigParameter::TYPE_BOOLEAN, true),
        ];

        $schema = (new ConfigSchema())->setParameters(...$options);
        $json   = \json_encode(['yo' => false, 'foo' => 'bar'], \JSON_THROW_ON_ERROR);
        $this->assertNotFalse($json);
        $config = $this->createFileConfig($json);
        $schema->validateConfig($config);
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws EmptyDirectoryException
     */
    public function testValidateHasInvalidValueAndMissingRequiredProperty(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode(ConfigException::SCHEMA_VALIDATION_ERRORS);
        $options = [
            ConfigParameter::create('foo', ConfigParameter::TYPE_STRING, true),
            ConfigParameter::create('nothing', ConfigParameter::TYPE_STRING, false),
            ConfigParameter::create('yo', ConfigParameter::TYPE_BOOLEAN, true),
        ];
        $schema  = (new ConfigSchema())->setParameters(...$options);
        $json    = \json_encode(['foo' => 42, 'nothing' => true], \JSON_THROW_ON_ERROR);
        $this->assertNotFalse($json);
        $config = $this->createFileConfig($json);
        $schema->validateConfig($config);
    }
}
