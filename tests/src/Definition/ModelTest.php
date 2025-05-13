<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\DefinitionException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ModelTest
 *
 * @package Jtl\Connector\Core\Test\Definition
 */
class ModelTest extends TestCase
{
    /**
     * @return void
     * @throws AssertionFailedError
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPropertyMappingsMatchModelIdentities(): void
    {
        $modelNamespace = 'Jtl\\Connector\\Core\\Model';

        $definition           = new Model();
        $definitionReflection = new \ReflectionClass($definition);
        $propertyMappings     = $definitionReflection->getProperty('propertyMappings');
        $propertyMappings->setAccessible(true);

        $exceptions = [Model::PRODUCT_ATTRIBUTE];

        $mappings = $propertyMappings->getValue($definition);
        $this->assertIsArray($mappings);
        foreach ($mappings as $modelName => $identityMappings) {
            if (\in_array($modelName, $exceptions, true)) {
                continue;
            }

            $modelClass = \sprintf("%s\\%s", $modelNamespace, $modelName);
            $model      = new $modelClass();

            foreach ($identityMappings as $propertyName => $identityType) {
                if (!\property_exists($model, $propertyName)) {
                    $this->fail(\sprintf('Object %s has not property %s', \get_class($model), $propertyName));
                }
            }
        }
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMappingsMatchIdentityType(): void
    {
        $definition           = new Model();
        $definitionReflection = new \ReflectionClass($definition);
        $propertyMappings     = $definitionReflection->getProperty('mappings');
        $propertyMappings->setAccessible(true);

        $mappings = $propertyMappings->getValue($definition);
        $this->assertIsArray($mappings);
        foreach ($mappings as $mapping) {
            $this->assertTrue(IdentityType::isType($mapping));
        }
    }

    /**
     * @dataProvider getModelByTypeProvider
     *
     * @param int               $identityType
     * @param \Exception|string $expectedResult
     * @param bool              $shouldThrowException
     *
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    public function testGetModelByType(
        int               $identityType,
        \Exception|string $expectedResult,
        bool              $shouldThrowException = false
    ): void {
        if ($shouldThrowException) {
            if ($expectedResult instanceof \Exception === false) {
                throw new \InvalidArgumentException(
                    'If method should throw an Exception, $expected result must be instance of ' . \Exception::class
                );
            }
            $this->expectExceptionObject($expectedResult);
        }

        $result = Model::getModelByType($identityType);

        if ($shouldThrowException === false) {
            $this->assertSame($expectedResult, $result);
        }
    }

    /**
     * @return array<int, array{0: int, 1: string|DefinitionException, 2?: true}>
     */
    public function getModelByTypeProvider(): array
    {
        return [
            [IdentityType::CATEGORY, Model::CATEGORY],
            [0, DefinitionException::unknownIdentityType(0), true],
            [-999999999999999999, DefinitionException::unknownIdentityType(-999999999999999999), true],
            [IdentityType::SPECIFIC_VALUE_IMAGE, Model::SPECIFIC_VALUE_IMAGE]
        ];
    }

    /**
     * @return void
     * @throws DefinitionException
     */
    public function testGetIdentityTypeModelIsInvalid(): void
    {
        $modelName = 'InvalidModelName';

        $this->expectExceptionObject(DefinitionException::unknownModel($modelName));

        Model::getIdentityType($modelName);
    }

    /**
     * @dataProvider isIdentityPropertyProvider
     *
     * @param string $modelName
     * @param string $propertyName
     * @param bool   $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsIdentityPropertyInvalidPropertyName(
        string $modelName,
        string $propertyName,
        bool   $expectedResult
    ): void {
        $result = Model::isIdentityProperty($modelName, $propertyName);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: bool}>
     */
    public function isIdentityPropertyProvider(): array
    {
        return [
            [Model::SHIPMENT, 'id', false],
            [Model::PRODUCT, 'id', true],
            [Model::PRODUCT, 'id ', false],
            [Model::PRODUCT, ' ', false],
            [Model::PRODUCT, 'ID', false],
            [Model::CATEGORY, 'ID', false],
            [Model::CATEGORY, 'id', true]
        ];
    }

    /**
     * @dataProvider modelNameProvider
     *
     * @param string $modelName
     * @param bool   $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsModel(string $modelName, bool $expectedResult): void
    {
        $this->assertEquals($expectedResult, Model::isModel($modelName));
    }

    /**
     * @dataProvider modelNameProvider
     *
     * @param string $modelName
     * @param bool   $isModelName
     *
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetRelationType(string $modelName, bool $isModelName): void
    {
        if (!$isModelName) {
            $this->expectExceptionObject(DefinitionException::unknownModel($modelName));
        }

        $this->assertEquals(\lcfirst($modelName), Model::getRelationType($modelName));
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     */
    public function modelNameProvider(): array
    {
        return [
            [Model::PRODUCT, true],
            [Model::CATEGORY, true],
            [Model::PRODUCT_TO_CATEGORY, true],
            [Model::UNIT, true],
            [Model::MANUFACTURER, true],
            [Model::MANUFACTURER_IMAGE, true],
            ['Product2Categoryy', false],
            [' Product2Category', false],
            ['PRODUCT2CATEGORY', false],
            [Model::SHIPMENT, true]
        ];
    }
}
