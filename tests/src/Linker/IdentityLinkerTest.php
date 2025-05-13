<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Linker;

use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\LinkerException;
use Jtl\Connector\Core\Linker\IdentityLinker;
use Jtl\Connector\Core\Mapper\PrimaryKeyMapperInterface;
use Jtl\Connector\Core\Model\Category;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\ProductVariation;
use Jtl\Connector\Core\Model\ProductWarehouseInfo;
use Jtl\Connector\Core\Model\ShippingClass;
use Jtl\Connector\Core\Test\TestCase;
use Mockery\Exception\RuntimeException;
use Mockery\LegacyMockInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class IdentityLinkerTest
 *
 * @package Jtl\Connector\Core\Linker
 */
class IdentityLinkerTest extends TestCase
{
    /**
     * @dataProvider hostIdDataProvider
     *
     * @param mixed $hostId
     * @param bool  $shouldBeValid
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function testHostIdValidator(mixed $hostId, bool $shouldBeValid): void
    {
        $isValid = $this->createLinker()->isValidHostId($hostId);

        $this->assertEquals($shouldBeValid, $isValid);
    }

    /**
     * @param PrimaryKeyMapperInterface|null $mockedPrimaryKeyMapper
     *
     * @return IdentityLinker
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createLinker(?PrimaryKeyMapperInterface $mockedPrimaryKeyMapper = null): IdentityLinker
    {
        if ($mockedPrimaryKeyMapper === null) {
            $mockedPrimaryKeyMapper = $this->createPrimaryKeyMapperMock();
        }

        return new IdentityLinker($mockedPrimaryKeyMapper);
    }

    /**
     * @param array<int|null>    $hostId
     * @param array<string|null> $endpointId
     *
     * @return PrimaryKeyMapperInterface&LegacyMockInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function createPrimaryKeyMapperMock(
        array $hostId = [1],
        array $endpointId = ['1']
    ): PrimaryKeyMapperInterface&LegacyMockInterface {
        /** @var PrimaryKeyMapperInterface&LegacyMockInterface $primaryKeyMapper */
        $primaryKeyMapper = \Mockery::mock(PrimaryKeyMapperInterface::class);
        $primaryKeyMapper->shouldReceive('save')->andReturnTrue();                    //@phpstan-ignore-line
        $primaryKeyMapper->shouldReceive('delete')->andReturnTrue();                  //@phpstan-ignore-line
        $primaryKeyMapper->shouldReceive('clear')->andReturnTrue();                   //@phpstan-ignore-line
        $primaryKeyMapper->shouldReceive('getHostId')->andReturn(...$hostId);         //@phpstan-ignore-line
        $primaryKeyMapper->shouldReceive('getEndpointId')->andReturn(...$endpointId); //@phpstan-ignore-line

        return $primaryKeyMapper;
    }

    /**
     * @return array<int, array<int, int|bool|null>>
     * @throws \Exception
     */
    public function hostIdDataProvider(): array
    {
        return [
            [0, false],
            [null, false],
            [$this->createHostId(), true]
        ];
    }

    /**
     * @dataProvider endpointIdDataProvider
     *
     * @param mixed $endpointId
     * @param bool  $shouldBeValid
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function testEndpointIdValidator(mixed $endpointId, bool $shouldBeValid): void
    {
        $isValid = $this->createLinker()->isValidEndpointId($endpointId);
        $this->assertEquals($shouldBeValid, $isValid);
    }

    /**
     * @return array<int, array<int, int|bool|string|null>>
     * @throws \Exception
     */
    public function endpointIdDataProvider(): array
    {
        return [
            [0, false],
            [null, false],
            [$this->createEndpointId(), true]
        ];
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws ReflectionException
     * @throws \Exception
     */
    public function testCache(): void
    {
        $linker = $this->createLinker();

        $reflection = new \ReflectionClass($linker);

        $deleteCache = $reflection->getMethod('deleteCache');
        $deleteCache->setAccessible(true);

        $saveCache = $reflection->getMethod('saveCache');
        $saveCache->setAccessible(true);

        $checkCache = $reflection->getMethod('checkCache');
        $checkCache->setAccessible(true);

        $loadCache = $reflection->getMethod('loadCache');
        $loadCache->setAccessible(true);

        $cacheType  = IdentityLinker::CACHE_TYPE_ENDPOINT;
        $type       = IdentityType::CATEGORY;
        $endpointId = $this->createEndpointId();
        $hostId     = $this->createHostId();

        $this->assertEmpty($linker->getCache());

        $saveCache->invoke($linker, $endpointId, $hostId, $type, $cacheType);
        $this->assertCount(1, $linker->getCache());

        $cacheValue = $loadCache->invoke($linker, $endpointId, $type, $cacheType);
        $this->assertEquals($hostId, $cacheValue);

        $cacheExists = $checkCache->invoke($linker, $endpointId, $type, $cacheType);
        $this->assertTrue($cacheExists);

        $cacheDoesntExists = $checkCache->invoke($linker, $endpointId, $type, IdentityLinker::CACHE_TYPE_HOST);
        $this->assertFalse($cacheDoesntExists);

        $deleteCache->invoke($linker, $type, $cacheType, $endpointId, $hostId);
        $this->assertEmpty($linker->getCache());
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     */
    public function testHostIdResolver(): void
    {
        $linker = $this->createLinker();

        $hostId = $linker->getHostId(Model::CATEGORY, 'id', '1');

        $this->assertSame(1, $hostId);

        $modelName = Model::CATEGORY;
        $property  = 'property_doesnt_exists';
        $this->expectExceptionObject(DefinitionException::unknownIdentityProperty($modelName, $property));

        $linker->getHostId($modelName, $property, '30');
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     */
    public function testEndpointIdResolver(): void
    {
        $linker = $this->createLinker();

        $endpointId = $linker->getEndpointId(Model::CATEGORY, 'id', 1);

        $this->assertSame('1', $endpointId);

        $modelName = Model::CATEGORY;
        $property  = 'property_doesnt_exists';
        $this->expectExceptionObject(DefinitionException::unknownIdentityProperty($modelName, $property));

        $linker->getEndpointId($modelName, $property, 30);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function testIdentityClear(): void
    {
        $linker = $this->createLinker();

        $clearResult = $linker->clear();
        $this->assertTrue($clearResult);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function testIdentitySave(): void
    {
        $endpointId   = $this->createEndpointId();
        $hostId       = $this->createHostId();
        $modelName    = Model::CATEGORY;
        $property     = 'parentCategoryId';
        $identityType = Model::getIdentityType($modelName);

        $linker = $this->createLinker();

        $saveResult = $linker->save($endpointId, $hostId, $modelName, $property);
        $this->assertTrue($saveResult);

        $reflection = new \ReflectionClass($linker);
        $checkCache = $reflection->getMethod('checkCache');
        $checkCache->setAccessible(true);

        $cacheExists = $checkCache->invoke($linker, $endpointId, $identityType, IdentityLinker::CACHE_TYPE_ENDPOINT);
        $this->assertTrue($cacheExists);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testIdentityDelete(): void
    {
        $endpointId   = $this->createEndpointId();
        $hostId       = $this->createHostId();
        $modelName    = Model::CATEGORY;
        $property     = 'id';
        $identityType = Model::getIdentityType($modelName);

        $primaryKeyMapper = $this->createPrimaryKeyMapperMock([0]);
        $linker           = $this->createLinker($primaryKeyMapper);

        $reflection = new \ReflectionClass($linker);
        $checkCache = $reflection->getMethod('checkCache');
        $checkCache->setAccessible(true);

        $linker->save($endpointId, $hostId, $modelName, $property);

        $deleteResult = $linker->delete($modelName, $endpointId, $hostId);
        $this->assertTrue($deleteResult);

        $cacheExists = $checkCache->invoke($linker, $endpointId, $identityType, IdentityLinker::CACHE_TYPE_ENDPOINT);
        $this->assertFalse($cacheExists);

        $endpointIdExists = $linker->endpointIdExists(Model::CATEGORY, $endpointId);
        $this->assertFalse($endpointIdExists);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testLinkModel(): void
    {
        $productHostId     = $this->createHostId();
        $productEndpointId = $this->createEndpointId();

        $shippingClassHostId     = $this->createHostId();
        $shippingClassEndpointId = $this->createEndpointId();

        $primaryKeyMapper = $this->createPrimaryKeyMapperMock(
            [$productHostId, $shippingClassHostId],
            [$productEndpointId, $shippingClassEndpointId]
        );
        $linker           = $this->createLinker($primaryKeyMapper);

        $product = new Product();
        $product->setCreationDate(new \DateTimeImmutable());
        $product->setId(new Identity($productEndpointId, $productHostId));

        $shippingClass = new ShippingClass();
        $shippingClass->setId(new Identity($shippingClassEndpointId, $shippingClassHostId));

        $linker->linkModel($product);

        $hostId = $linker->getHostId(Model::PRODUCT, 'id', $productEndpointId);
        $this->assertSame($productHostId, $hostId);

        $endpointId = $linker->getEndpointId(Model::PRODUCT, 'id', $productHostId);
        $this->assertSame($productEndpointId, $endpointId);

        $endpointIdExists = $linker->endpointIdExists(Model::PRODUCT, $productEndpointId);
        $this->assertTrue($endpointIdExists);

        $hostIdExists = $linker->hostIdExists(Model::PRODUCT, $productHostId);
        $this->assertTrue($hostIdExists);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testUnlinkModel(): void
    {
        $primaryKeyMapper = $this->createPrimaryKeyMapperMock([0]);
        $linker           = $this->createLinker($primaryKeyMapper);

        $expectedHostId = $this->createHostId();
        $endpointId     = $this->createEndpointId();

        $category = new Category();
        $category->setId(new Identity($endpointId, $expectedHostId));
        $linker->linkModel($category, true);

        $returnedHostId = $linker->getHostId(Model::CATEGORY, 'id', $endpointId);
        $this->assertSame(0, $returnedHostId);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testLinkCollection(): void
    {
        $expectedHostId = $this->createHostId();
        $endpointId     = $this->createEndpointId();

        $primaryKeyMapper = $this->createPrimaryKeyMapperMock([$expectedHostId], [$endpointId]);

        $linker = $this->createLinker($primaryKeyMapper);

        $product = new Product();
        $product->setCreationDate(new \DateTimeImmutable());
        $productVariation = new ProductWarehouseInfo();
        $productVariation->setWarehouseId(new Identity($endpointId, $expectedHostId));
        $product->addWarehouseInfo($productVariation);

        $linker->linkModel($product);

        $returnedHostId = $linker->getHostId(Model::PRODUCT_WAREHOUSE_INFO, 'warehouseId', $endpointId);
        $this->assertSame($expectedHostId, $returnedHostId);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testUnlinkCollection(): void
    {
        $expectedHostId = $this->createHostId();
        $endpointId     = $this->createEndpointId();

        $primaryKeyMapper = $this->createPrimaryKeyMapperMock([$expectedHostId, null], [$endpointId, null]);
        $linker           = $this->createLinker($primaryKeyMapper);

        $product = new Product();
        $product->setCreationDate(new \DateTimeImmutable());
        $productVariation = new ProductVariation();
        $productVariation->setId(new Identity($endpointId, $expectedHostId));
        $product->addVariation($productVariation);

        $linker->linkModel($product);

        $isHostIdExists = $linker->hostIdExists(Model::PRODUCT_VARIATION, $expectedHostId);
        $this->assertTrue($isHostIdExists);

        $linker->linkModel($product, true);

        $isHostIdExists = $linker->hostIdExists(Model::PRODUCT_VARIATION, $expectedHostId);
        $this->assertFalse($isHostIdExists);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws \Exception
     */
    public function testLinkIdentityList(): void
    {
        $expectedHostId     = $this->createHostId();
        $expectedEndpointId = $this->createEndpointId();
        $modelName          = Model::CONFIG_GROUP;

        $primaryKeyMapper = $this->createPrimaryKeyMapperMock([$expectedHostId, 0]);
        $linker           = $this->createLinker($primaryKeyMapper);

        $identity = new Identity($expectedEndpointId);

        $linker->linkIdentity($identity, $modelName, 'id');

        $this->assertEquals($expectedHostId, $identity->getHost());
        $hostId = $linker->getHostId($modelName, 'id', $expectedEndpointId);
        $this->assertEquals($hostId, $expectedHostId);

        $linker->linkIdentity($identity, $modelName, 'id', true);
        $hostId = $linker->getHostId($modelName, 'id', $expectedEndpointId);
        $this->assertNotEquals($expectedHostId, $hostId);

        $endpointId = $linker->getEndpointId($modelName, 'id', $expectedHostId);
        $this->assertNotEquals($expectedEndpointId, $endpointId);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        \Mockery::close();
    }
}
