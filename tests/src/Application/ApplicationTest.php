<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Application;

use DI\Container;
use DI\Definition\Exception\InvalidDefinition;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Annotations\AnnotationException;
use Jawira\CaseConverter\CaseConverterException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JsonException;
use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Application\Request;
use Jtl\Connector\Core\Application\Response;
use Jtl\Connector\Core\Authentication\TokenValidatorInterface;
use Jtl\Connector\Core\Config\ArrayConfig;
use Jtl\Connector\Core\Config\ConfigParameter;
use Jtl\Connector\Core\Config\ConfigSchema;
use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Connector\ConnectorInterface;
use Jtl\Connector\Core\Definition\Action;
use Jtl\Connector\Core\Definition\Controller;
use Jtl\Connector\Core\Definition\ErrorCode;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\ApplicationException;
use Jtl\Connector\Core\Exception\CompressionException;
use Jtl\Connector\Core\Exception\ConfigException;
use Jtl\Connector\Core\Exception\DatabaseException;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\FileNotFoundException;
use Jtl\Connector\Core\Exception\LoggerException;
use Jtl\Connector\Core\Exception\RpcException;
use Jtl\Connector\Core\Exception\SessionException;
use Jtl\Connector\Core\Mapper\PrimaryKeyMapperInterface;
use Jtl\Connector\Core\Model\AbstractImage;
use Jtl\Connector\Core\Model\Ack;
use Jtl\Connector\Core\Model\Category;
use Jtl\Connector\Core\Model\Generator\AbstractModelFactory;
use Jtl\Connector\Core\Model\Manufacturer;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\ProductImage;
use Jtl\Connector\Core\Model\QueryFilter;
use Jtl\Connector\Core\Model\Statistic;
use Jtl\Connector\Core\Rpc\Error;
use Jtl\Connector\Core\Rpc\RequestPacket;
use Jtl\Connector\Core\Rpc\ResponsePacket;
use Jtl\Connector\Core\Serializer\SerializerBuilder;
use Jtl\Connector\Core\Session\SessionHandlerInterface;
use Jtl\Connector\Core\Subscriber\FeaturesSubscriber;
use Jtl\Connector\Core\Subscriber\RequestParamsTransformSubscriber;
use Jtl\Connector\Core\Test\Stub\Controller\TransactionalControllerStub;
use Jtl\Connector\Core\Test\TestCase;
use Jtl\Connector\Core\Utilities\Str;
use MyPlugin\Bootstrap;
use Noodlehaus\Exception\EmptyDirectoryException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\CannotUseOnlyMethodsException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\ClassIsReadonlyException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Throwable;
use TypeError;

/**
 * Class ApplicationTest
 *
 * @package Jtl\Connector\Core\Application
 */
class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public function testHandleRequestControllerClassNotFoundException(): void
    {
        $application = $this->createInitializedApplication();
        $connector   = $this->createConnector();
        $request     = Request::create(Controller::PRODUCT, Action::PUSH, [new Product()]);
        $this->expectException(ApplicationException::class);
        $this->invokeMethodFromObject($application, 'handleRequest', $connector, $request);
    }

    /**
     * @param ConfigSchema|null        $configSchema
     * @param string|null              $connectorDir
     * @param CoreConfigInterface|null $config
     *
     * @return Application
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    protected function createInitializedApplication(
        ?ConfigSchema        $configSchema = null,
        ?string              $connectorDir = null,
        ?CoreConfigInterface $config = null
    ): Application {
        $sessionHandler = $this->createMock(SessionHandlerInterface::class);
        if (\is_null($configSchema)) {
            $configSchema = (new ConfigSchema())->setParameters(
                ...
                ConfigSchema::createDefaultParameters($this->connectorDir)
            );
        }

        if (\is_null($config)) {
            $config = new ArrayConfig($configSchema->getDefaultValues());
        }

        $app = $this->createApplication($configSchema, $connectorDir, $config);
        $app->setSessionHandler($sessionHandler);
        $app->getContainer()->set(
            PrimaryKeyMapperInterface::class,
            $this->createMock(PrimaryKeyMapperInterface::class)
        );
        $app->getContainer()->set(SessionHandlerInterface::class, $sessionHandler);
        $app->getContainer()->set(TokenValidatorInterface::class, $this->createMock(TokenValidatorInterface::class));

        return $app;
    }

    /**
     * @param ConfigSchema|null        $configSchema
     * @param string|null              $connectorDir
     * @param CoreConfigInterface|null $config
     *
     * @return Application
     * @throws ApplicationException
     * @throws ConfigException
     * @throws DependencyException
     * @throws LoggerException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws InvalidDefinition
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws LogicException
     * @throws \LogicException
     * @throws EmptyDirectoryException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    protected function createApplication(
        ?ConfigSchema        $configSchema = null,
        ?string              $connectorDir = null,
        ?CoreConfigInterface $config = null
    ): Application {
        if (\is_null($configSchema)) {
            $configSchema = new ConfigSchema();
        }

        if (\is_null($config)) {
            $config = new ArrayConfig([]);
        }

        if (\is_null($connectorDir)) {
            $connectorDir = $this->connectorDir;
        }

        return new Application($connectorDir, $config, $configSchema);
    }

    /**
     * @param string $controllerNamespace
     * @param bool   $tokenValidatorValidateValue
     *
     * @return ConnectorInterface&MockObject
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function createConnector(
        string $controllerNamespace = '',
        bool   $tokenValidatorValidateValue = true
    ): ConnectorInterface&MockObject {
        $tokenValidator = $this->createMock(TokenValidatorInterface::class);
        $tokenValidator->expects($this->any())->method('validate')->willReturn($tokenValidatorValidateValue);
        $pkMapper  = $this->createMock(PrimaryKeyMapperInterface::class);
        $connector = $this->createMock(ConnectorInterface::class);
        $connector->expects($this->any())->method('initialize');
        $connector->expects($this->any())->method('getControllerNamespace')->willReturn($controllerNamespace);
        $connector->expects($this->any())->method('getTokenValidator')->willReturn($tokenValidator);
        $connector->expects($this->any())->method('getPrimaryKeyMapper')->willReturn($pkMapper);

        return $connector;
    }

    /**
     * @dataProvider controllerActionsDataProvider
     *
     * @param string $action
     * @param mixed  $parameter
     *
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testHandleRequestControllerAction(string $action, mixed $parameter): void
    {
        $mock        = $this->createMock(Product::class);
        $application = $this->createInitializedApplication();
        $connector   = $this->createConnector();
        $controller  = $this->createTransactionalController();
        $application->getContainer()->set(Controller::PRODUCT, $controller);
        $request  = Request::create(Controller::PRODUCT, $action, [$parameter]);
        $response = $this->invokeMethodFromObject($application, 'handleRequest', $connector, $request);
        $this->assertInstanceOf(Response::class, $response);
        $result = $response->getResult();

        switch ($action) {
            case Action::STATISTIC:
                $this->assertInstanceOf(Statistic::class, $result);
                $this->assertSame(150, $result->getAvailable());
                break;
            case Action::DELETE:
            case Action::PUSH:
                $this->assertIsArray($result);
                $this->assertArrayHasKey(0, $result);
                $this->assertInstanceOf(Product::class, $result[0]);
                break;
            case Action::PULL:
                $this->assertIsArray($result);
                $this->assertArrayHasKey(0, $result);
                $this->assertArrayHasKey(1, $result);
                $this->assertInstanceOf(Product::class, $result[0]);
                $this->assertInstanceOf(Product::class, $result[1]);
                break;
        }
    }

    /**
     * @param bool $commitThrowsException
     *
     * @return TransactionalControllerStub
     */
    public function createTransactionalController(bool $commitThrowsException = false): TransactionalControllerStub
    {
        return new TransactionalControllerStub($commitThrowsException);
    }

    /**
     * @return array<int, array{0: string, 1: QueryFilter|Product}>
     */
    public function controllerActionsDataProvider(): array
    {
        $product = new Product();
        $product->setCreationDate(new \DateTimeImmutable());

        return [
            [
                Action::STATISTIC,
                new QueryFilter(),
            ],
            [
                Action::DELETE,
                $product,
            ],
            [
                Action::PULL,
                new QueryFilter(),
            ],
            [
                Action::PUSH,
                $product,
            ],
        ];
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testHandleRequestTransactionalMethodsCalls(): void
    {
        $application = $this->createInitializedApplication();
        $connector   = $this->createConnector();
        $controller  = $this->createMock(TransactionalControllerStub::class);
        $application->getContainer()->set(Controller::CATEGORY, $controller);
        $category = new Category();

        $controller->expects($this->once())->method('delete')->with($category)->willReturn([$category]);
        $controller->expects($this->once())->method('beginTransaction');
        $controller->expects($this->once())->method('commit');
        $controller->expects($this->never())->method('rollback');

        $request = Request::create(Controller::CATEGORY, Action::DELETE, [$category]);
        /** @var Response $response */
        $response = $this->invokeMethodFromObject($application, 'handleRequest', $connector, $request);
        $result   = $response->getResult();
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws Exception
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public function testHandleRequestTransactionalControllerFail(): void
    {
        $this->expectException(\RuntimeException::class);
        $category    = new Category();
        $application = $this->createInitializedApplication();
        $connector   = $this->createConnector();

        $controller = $this->createMock(TransactionalControllerStub::class);
        $controller->expects($this->once())->method('delete')
                   ->with($category)->willThrowException(new \RuntimeException());
        $controller->expects($this->once())->method('beginTransaction');
        $controller->expects($this->never())->method('commit');
        $controller->expects($this->once())->method('rollback');
        $application->getContainer()->set(Controller::CATEGORY, $controller);

        $request = Request::create(Controller::CATEGORY, Action::DELETE, [$category]);
        $this->invokeMethodFromObject($application, 'handleRequest', $connector, $request);
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testHandleRequestControllerClassNeedToBeInitialized(): void
    {
        $application = $this->createInitializedApplication();
        $connector   = $this->createConnector();
        $ack         = new Ack();
        $request     = Request::create(Controller::CONNECTOR, Action::ACK, [$ack]);
        /** @var Response $response */
        $response = $this->invokeMethodFromObject($application, 'handleRequest', $connector, $request);

        $this->assertNotEmpty($response->getResult());
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws NotFoundException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws SessionException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testPrepareContainer(): void
    {
        $config      = $this->createConfig(['foo' => 'you', 'bar' => 'jau']);
        $connector   = $this->createConnector(ConnectorInterface::class);
        $application = $this->createApplication(null, null, $config);
        $container   = $application->getContainer();

        $this->assertFalse($container->has(CoreConfigInterface::class));
        $this->assertFalse($container->has(TokenValidatorInterface::class));
        $this->assertFalse($container->has(PrimaryKeyMapperInterface::class));
        $this->assertFalse($container->has(SessionHandlerInterface::class));
        $this->invokeMethodFromObject($application, 'prepareContainer', $connector);
        $this->assertEquals($container->get(CoreConfigInterface::class), $config);
        $this->assertEquals($container->get(TokenValidatorInterface::class), $connector->getTokenValidator());
        $this->assertEquals($container->get(PrimaryKeyMapperInterface::class), $connector->getPrimaryKeyMapper());
        $this->assertEquals($container->get(SessionHandlerInterface::class), $application->getSessionHandler());
    }

    /**
     * @return void
     * @throws ConfigException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws UnknownTypeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPrepareConfigSetDefaultParameters(): void
    {
        $defaultParameters = ConfigSchema::createDefaultParameters($this->connectorDir);
        $schema            = new ConfigSchema();
        $application       = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        foreach ($defaultParameters as $parameter) {
            $this->assertFalse($schema->hasParameter($parameter->getKey()));
        }
        $this->invokeMethodFromObject(
            $application,
            'prepareConfig',
            $this->connectorDir,
            $this->createConfig(),
            $schema
        );
        foreach ($defaultParameters as $parameter) {
            $this->assertEquals($parameter, $schema->getParameter($parameter->getKey()));
        }
    }

    /**
     * @return void
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPrepareConfigurationSetDefaultValuesInConfig(): void
    {
        $schema = (new ConfigSchema())
            ->setParameter(ConfigParameter::create('foo', ConfigParameter::TYPE_INTEGER, true, false, 42))
            ->setParameter(ConfigParameter::create('bar', ConfigParameter::TYPE_BOOLEAN, false))
            ->setParameter(ConfigParameter::create('baz', ConfigParameter::TYPE_STRING, true, true, 'yes'));

        $config = $this->createConfig();

        $this->assertFalse($config->has('foo'));
        $this->assertFalse($config->has('bar'));
        $this->assertFalse($config->has('baz'));

        $application = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $this->invokeMethodFromObject($application, 'prepareConfig', '', $config, $schema);

        $this->assertEquals(42, $config->get('foo'));
        $this->assertFalse($config->has('bar'));
        $this->assertEquals('yes', $config->get('baz'));
    }

    /**
     * @return void
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testLoadPlugins(): void
    {
        $config          = $this->createMock(CoreConfigInterface::class);
        $container       = $this->createMock(Container::class);
        $eventDispatcher = $this->createMock(EventDispatcher::class);
        $app             = $this->getMockBuilder(Application::class)->disableOriginalConstructor()->getMock();
        $myPluginDirSrc  = \sprintf('%s/fixtures/MyPlugin', $this->connectorDir);
        $myPluginDirDst  = \sprintf('%s/plugins/MyPlugin', $this->connectorDir);
        \mkdir($myPluginDirDst, 0777, true);
        $data = \file_get_contents(\sprintf('%s/Bootstrap.php', $myPluginDirSrc));
        \file_put_contents(\sprintf('%s/Bootstrap.php', $myPluginDirDst), $data);
        $this->assertFalse(\class_exists(Bootstrap::class));
        $this->invokeMethodFromObject($app, 'loadPlugins', $config, $container, $eventDispatcher, $myPluginDirDst);
        $this->assertTrue(\class_exists(Bootstrap::class));
    }

    /**
     * @return void
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws CompressionException
     * @throws ConfigException
     * @throws DefinitionException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws FileNotFoundException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws JsonException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws NotFoundException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RpcException
     * @throws RuntimeException
     * @throws SessionException
     * @throws Throwable
     * @throws UnknownTypeException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws CaseConverterException
     * @throws CannotUseOnlyMethodsException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRun(): void
    {
        $serializer = SerializerBuilder::create()->build();
        $factory    = AbstractModelFactory::createFactory(Model::MANUFACTURER);
        $id         = $factory->getFaker()->uuid;
        /** @var Manufacturer $manufacturer */
        $manufacturer      = $factory->makeOne();
        $manufacturerArray = $serializer->toArray($manufacturer);
        $requestPacket     = (new RequestPacket())->setJtlrpc('2.0')
                                                  ->setMethod('manufacturer.push')
                                                  ->setParams([$manufacturerArray])
                                                  ->setId($id)
                                                  ->toArray();
        $responsePacket    = (new ResponsePacket())->setJtlrpc('2.0')->setId($id)->setResult([$manufacturer]);
        $_POST['jtlrpc']   = \json_encode($requestPacket, \JSON_THROW_ON_ERROR);

        $connector    = $this->createConnector('Jtl\Connector\Core\Test\Stub\Controller');
        $config       = $this->createConfig();
        $configSchema = $this->getMockBuilder(ConfigSchema::class)->onlyMethods(['validateConfig'])->getMock();
        $controller   = $this->createMock(TransactionalControllerStub::class);

        /** @var Application&MockObject $app */
        $app = $this->getMockBuilder(Application::class)
                    ->setConstructorArgs([$this->connectorDir, $config, $configSchema])
                    ->onlyMethods(['startSession', 'loadPlugins', 'prepareContainer'])
                    ->getMock();

        $app->setSessionHandler($this->createMock(SessionHandlerInterface::class));
        $app->getContainer()->set(SessionHandlerInterface::class, $this->createMock(SessionHandlerInterface::class));
        $app->getContainer()->set(TokenValidatorInterface::class, $this->createMock(TokenValidatorInterface::class));
        $app->getContainer()->set(
            PrimaryKeyMapperInterface::class,
            $this->createMock(PrimaryKeyMapperInterface::class)
        );
        $app->getContainer()->set(Controller::MANUFACTURER, $controller);

        $app->expects($this->once())->method('startSession');
        $app->expects($this->once())->method('loadPlugins');
        $app->expects($this->once())->method('prepareContainer');

        $connector
            ->expects($this->once())
            ->method('initialize')
            ->with($config, $app->getContainer(), $app->getEventDispatcher());

        $configSchema->expects($this->once())->method('validateConfig')->with($config);
        $controller->expects($this->once())->method('push')->willReturn([$manufacturer]);
        $this->expectOutputString(\json_encode($responsePacket->toArray($serializer), \JSON_THROW_ON_ERROR));

        $app->run($connector);

        $eventDispatcher   = $app->getEventDispatcher();
        $rpcEventListeners = $eventDispatcher->getListeners('rpc.before');
        $this->assertGreaterThan(0, \count($rpcEventListeners));

        $requestParamsTransformSubscriberFound = false;
        foreach ($rpcEventListeners as $listener) {
            $this->assertIsArray($listener);
            $this->assertArrayHasKey(0, $listener);
            if ($listener[0] instanceof RequestParamsTransformSubscriber) {
                $requestParamsTransformSubscriberFound = true;
                break;
            }
        }

        $coreFeaturesListeners =
            $eventDispatcher->getListeners(Event::createCoreEventName('Connector', 'features', 'after'));
        $this->assertGreaterThan(0, $coreFeaturesListeners);

        $coreFeaturesListenerFound = false;
        foreach ($coreFeaturesListeners as $listener) {
            $this->assertIsArray($listener);
            $this->assertArrayHasKey(0, $listener);
            if ($listener[0] instanceof FeaturesSubscriber) {
                $coreFeaturesListenerFound = true;
                break;
            }
        }

        $this->assertTrue($requestParamsTransformSubscriberFound, 'No RequestParamsTransformSubscriber is registered');
        $this->assertTrue($coreFeaturesListenerFound, 'No CoreFeaturesSubscriber is registered');
    }

    /**
     * @return void
     * @throws ApplicationException
     * @throws CompressionException
     * @throws ConfigException
     * @throws DefinitionException
     * @throws DependencyException
     * @throws FileNotFoundException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LoggerException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws RpcException
     * @throws RuntimeException
     * @throws SessionException
     * @throws Throwable
     * @throws TypeError
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     */
    public function testRunInvalidRpcMethod(): void
    {
        $this->expectException(RpcException::class);
        $this->expectExceptionCode(ErrorCode::INVALID_REQUEST);
        $serializer      = SerializerBuilder::create()->build();
        $factory         = AbstractModelFactory::createFactory(Model::MANUFACTURER);
        $id              = $factory->getFaker()->uuid;
        $requestPacket   = (new RequestPacket())->setJtlrpc('2.0')
                                                ->setMethod('yoo')
                                                ->setParams([])
                                                ->setId($id)
                                                ->toArray();
        $_POST['jtlrpc'] = \json_encode($requestPacket, \JSON_THROW_ON_ERROR);
        $ex              = RpcException::invalidRequest();
        $error           = (new Error())->setCode(ErrorCode::INVALID_REQUEST)
                                        ->setMessage('Invalid request')
                                        ->setData(Error::createDataFromException($ex));
        $responsePacket  = (new ResponsePacket())->setJtlrpc('2.0')->setId($id)->setError($error);
        $this->expectOutputString(\json_encode($responsePacket->toArray($serializer), \JSON_THROW_ON_ERROR));
        $this->createApplication()->run($this->createConnector());
    }

    /**
     * @return void
     * @throws ApplicationException
     * @throws CompressionException
     * @throws ConfigException
     * @throws DefinitionException
     * @throws DependencyException
     * @throws FileNotFoundException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LoggerException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws RpcException
     * @throws RuntimeException
     * @throws SessionException
     * @throws Throwable
     * @throws TypeError
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     */
    public function testRunUnknownController(): void
    {
        $this->expectException(DefinitionException::class);
        $this->expectExceptionCode(ErrorCode::UNKNOWN_CONTROLLER);
        $serializer      = SerializerBuilder::create()->build();
        $factory         = AbstractModelFactory::createFactory(Model::MANUFACTURER);
        $id              = $factory->getFaker()->uuid;
        $requestPacket   = (new RequestPacket())->setJtlrpc('2.0')
                                                ->setMethod('foo.bar')
                                                ->setParams([])
                                                ->setId($id)
                                                ->toArray();
        $_POST['jtlrpc'] = \json_encode($requestPacket, \JSON_THROW_ON_ERROR);
        $ex              = DefinitionException::unknownController('foo');
        $error           = (new Error())
            ->setCode(ErrorCode::UNKNOWN_CONTROLLER)
            ->setMessage('Unknown controller (Foo)')
            ->setData(Error::createDataFromException($ex));

        $responsePacket = (new ResponsePacket())
            ->setJtlrpc('2.0')
            ->setId($id)
            ->setError($error);

        $this->expectOutputString(\json_encode($responsePacket->toArray($serializer), \JSON_THROW_ON_ERROR));

        $this->createApplication()
             ->run($this->createConnector());
    }

    /**
     * @return void
     * @throws ApplicationException
     * @throws ConfigException
     * @throws DefinitionException
     * @throws DependencyException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws LoggerException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws RpcException
     * @throws RuntimeException
     * @throws SessionException
     * @throws TypeError
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws JsonException
     * @throws CompressionException
     * @throws FileNotFoundException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws Throwable
     */
    public function testRunUnknownAction(): void
    {
        $this->expectException(DefinitionException::class);
        $this->expectExceptionCode(ErrorCode::UNKNOWN_ACTION);
        $serializer      = SerializerBuilder::create()->build();
        $factory         = AbstractModelFactory::createFactory(Model::MANUFACTURER);
        $id              = $factory->getFaker()->uuid;
        $requestPacket   = (new RequestPacket())->setJtlrpc('2.0')
                                                ->setMethod('category.bar')
                                                ->setParams([])
                                                ->setId($id)
                                                ->toArray();
        $_POST['jtlrpc'] = \json_encode($requestPacket, \JSON_THROW_ON_ERROR);
        $ex              = DefinitionException::unknownAction('bar');
        $error           = (new Error())->setCode(ErrorCode::UNKNOWN_ACTION)
                                        ->setMessage('Unknown action (bar)')
                                        ->setData(Error::createDataFromException($ex));
        $responsePacket  = (new ResponsePacket())->setJtlrpc('2.0')->setId($id)->setError($error);
        $this->expectOutputString(\json_encode($responsePacket->toArray($serializer), \JSON_THROW_ON_ERROR));

        $this->createApplication()
             ->run($this->createConnector());
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws CaseConverterException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DefinitionException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws FileException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     * @throws \UnexpectedValueException
     */
    public function testHandleImagePushWithFilesSentByWawi(): void
    {
        $serializer = SerializerBuilder::create()->build();
        $data       = \file_get_contents(\sprintf('%s/fixtures/images_push.json', \TEST_DIR));
        $this->assertNotFalse($data);
        $type = \sprintf('array<%s>', AbstractImage::class);
        /** @var ProductImage[] $images */
        $images           = $serializer->deserialize($data, $type, 'json');
        $uploadedFilePath = \sprintf('%s/fixtures/images_push.zip', \TEST_DIR);
        $file             = new UploadedFile(
            $uploadedFilePath,
            'images.zip',
            'application/octet-stream',
            \UPLOAD_ERR_OK,
            true
        );
        $filebag          = new FileBag(['file' => $file]);

        $request = $this->getMockBuilder(HttpRequest::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->files = $filebag;

        $app = $this->createApplication()
                    ->setHttpRequest($request);

        $this->invokeMethodFromObject($app, 'handleImagePush', ...$images);

        foreach ($images as $image) {
            $this->assertFileExists($image->getFilename());
            $expectedFilename =
                \sprintf('%d_%s.jpg', $image->getId()->getHost(), Str::toPascalCase($image->getRelationType()));
            $this->assertEquals(
                $expectedFilename,
                \substr($image->getFilename(), \strrpos($image->getFilename(), '/') + 1)
            );
        }
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ConfigException
     * @throws DependencyException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws InvalidDefinition
     * @throws LoggerException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testHandleImagePushUploadedFileNotFound(): void
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionCode(ErrorCode::REQUEST_ERROR);
        $serializer = SerializerBuilder::create()->build();
        $data       = \file_get_contents(\sprintf('%s/fixtures/images_push.json', \TEST_DIR));
        $this->assertNotFalse($data);
        $type = \sprintf('array<%s>', AbstractImage::class);
        /** @var ProductImage[] $images */
        $images = $serializer->deserialize($data, $type, 'json');
        $app    = $this->createApplication();
        $this->invokeMethodFromObject($app, 'handleImagePush', ...$images);
    }

    /**
     * @return void
     * @throws AnnotationException
     * @throws ApplicationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws ConfigException
     * @throws DependencyException
     * @throws DuplicateMethodException
     * @throws EmptyDirectoryException
     * @throws ExpectationFailedException
     * @throws FileException
     * @throws InvalidArgumentException
     * @throws InvalidDefinition
     * @throws InvalidMethodNameException
     * @throws LoggerException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws TypeError
     * @throws UnknownTypeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     * @throws \UnexpectedValueException
     */
    public function testHandleImagePushFileExtractionFailed(): void
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionCode(ErrorCode::SERVER_ERROR);
        $serializer = SerializerBuilder::create()->build();
        $data       = \file_get_contents(\sprintf('%s/fixtures/images_push.json', \TEST_DIR));
        $this->assertNotFalse($data);
        $type = \sprintf('array<%s>', AbstractImage::class);
        /** @var ProductImage[] $images */
        $images           = $serializer->deserialize($data, $type, 'json');
        $uploadedFilePath = \sprintf('%s/fixtures/images_push.json', \TEST_DIR);
        $file             = new UploadedFile(
            $uploadedFilePath,
            'images.zip',
            'application/octet-stream',
            \UPLOAD_ERR_OK,
            true
        );
        $filebag          = new FileBag(['file' => $file]);
        $request          = $this->getMockBuilder(HttpRequest::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $request->files = $filebag;
        $app            = $this->createApplication()->setHttpRequest($request);
        $this->invokeMethodFromObject($app, 'handleImagePush', ...$images);
    }
}
