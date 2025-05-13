<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Controller;

use InvalidArgumentException;
use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Authentication\TokenValidatorInterface;
use Jtl\Connector\Core\Connector\ConnectorInterface;
use Jtl\Connector\Core\Controller\ConnectorController;
use Jtl\Connector\Core\Exception\AuthenticationException;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\JsonException;
use Jtl\Connector\Core\Linker\ChecksumLinker;
use Jtl\Connector\Core\Linker\IdentityLinker;
use Jtl\Connector\Core\Model\Ack;
use Jtl\Connector\Core\Model\Authentication;
use Jtl\Connector\Core\Model\Features;
use Jtl\Connector\Core\Model\Session;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\CannotUseOnlyMethodsException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\ClassIsReadonlyException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use SessionHandlerInterface;

class ConnectorTest extends TestCase
{
    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \JsonException
     * @throws JsonException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws CannotUseOnlyMethodsException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws IncompatibleReturnValueException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFeatures(): void
    {
        $controller = $this->getMockBuilder(ConnectorController::class)
                           ->onlyMethods(['fetchFeaturesData'])
                           ->disableOriginalConstructor()
                           ->getMock();


        $jsonFeatures = ['entities' => ['Category' => ['push' => true]]];
        $controller->expects($this->once())->method('fetchFeaturesData')->willReturn($jsonFeatures);

        $features = $controller->features();

        $this->assertInstanceOf(Features::class, $features);
        $this->assertCount(1, $features->getEntities());
    }

    /**
     * @return void
     * @throws CaseConverterException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DefinitionException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAckEmpty(): void
    {
        $connector = $this->createConnectorController();
        $this->assertTrue($connector->ack(new Ack()));
    }

    /**
     * @param IdentityLinker|null          $linker
     * @param ChecksumLinker|null          $checksumLinker
     * @param SessionHandlerInterface|null $sessionHandler
     * @param TokenValidatorInterface|null $tokenValidator
     * @param string                       $featuresPath
     *
     * @return ConnectorController
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     */
    protected function createConnectorController(
        ?IdentityLinker           $linker = null,
        ?ChecksumLinker           $checksumLinker = null,
        ?\SessionHandlerInterface $sessionHandler = null,
        ?TokenValidatorInterface  $tokenValidator = null,
        string                   $featuresPath = ''
    ): ConnectorController {
        if (\is_null($linker)) {
            $linker = $this->createMock(IdentityLinker::class);
        }

        if (\is_null($checksumLinker)) {
            $checksumLinker = $this->createMock(ChecksumLinker::class);
        }

        if (\is_null($sessionHandler)) {
            $sessionHandler = $this->createMock(\SessionHandlerInterface::class);
        }

        if (\is_null($tokenValidator)) {
            $tokenValidator = $this->createMock(TokenValidatorInterface::class);
        }

        return new ConnectorController(
            $featuresPath,
            $checksumLinker,
            $linker,
            $sessionHandler,
            $tokenValidator
        );
    }

    /**
     * @return void
     * @throws CaseConverterException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DefinitionException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAckInvalidModelName(): void
    {
        $linker = $this->createMock(IdentityLinker::class);
        $linker->expects($this->exactly(3))->method('save')->willReturn(true);
        $connector = $this->createConnectorController($linker);
        $ack       = new Ack();
        $ack->setIdentities(
            [
                'Foo'      => [$this->createIdentity()],
                'Category' => [$this->createIdentity(), $this->createIdentity(), $this->createIdentity()],
            ]
        );

        $this->assertTrue($connector->ack($ack));
    }

    /**
     * @return void
     * @throws AuthenticationException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     */
    public function testAuthMissingToken(): void
    {
        $connector = $this->createConnectorController();

        $this->expectExceptionObject(AuthenticationException::tokenMissing());

        $auth = new Authentication();

        $connector->auth($auth);
    }

    /**
     * @return void
     * @throws AuthenticationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     */
    public function testAuthTokenIsInvalid(): void
    {
        $_SERVER['REMOTE_ADDR'] = '';
        $this->expectExceptionObject(AuthenticationException::failed());
        $tokenValidator = $this->createMock(TokenValidatorInterface::class);

        $tokenValidator
            ->expects($this->once())
            ->method('validate');

        $controller = $this->createConnectorController(null, null, null, $tokenValidator);

        $auth = (new Authentication())
            ->setToken(\md5((string)\time()));

        $controller->auth($auth);
    }

    /**
     * @return void
     * @throws AuthenticationException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAuthCorrect(): void
    {
        $tokenValidator = $this->createMock(TokenValidatorInterface::class);

        $tokenValidator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $connector = $this->createConnectorController(null, null, null, $tokenValidator);

        $auth = new Authentication();
        $auth->setToken(\md5((string)\time()));

        $session = $connector->auth($auth);

        $this->assertInstanceOf(Session::class, $session);
        $this->assertNotEmpty($session->getLifetime());
    }

    /**
     * @return void
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIdentify(): void
    {
        $endpointVersion = '1.0';
        $platformName    = 'ConnectorPlatform';
        $platformVersion = '0.1';

        $connector = $this->createMock(ConnectorInterface::class);
        $connector->expects($this->once())->method('getEndpointVersion')->willReturn($endpointVersion);
        $connector->expects($this->once())->method('getPlatformVersion')->willReturn($platformVersion);
        $connector->expects($this->once())->method('getPlatformName')->willReturn($platformName);

        $controller              = $this->createConnectorController();
        $connectorIdentification = $controller->identify($connector);

        $this->assertSame($endpointVersion, $connectorIdentification->getEndpointVersion());
        $this->assertSame($platformName, $connectorIdentification->getPlatformName());
        $this->assertSame($platformVersion, $connectorIdentification->getPlatformVersion());
        $this->assertSame(Application::PROTOCOL_VERSION, $connectorIdentification->getProtocolVersion());
    }

    /**
     * @return void
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DefinitionException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClearSuccess(): void
    {
        $linker = $this->createMock(IdentityLinker::class);
        $linker->expects($this->once())->method('clear')->willReturn(true);
        $controller = $this->createConnectorController($linker);

        $response = $controller->clear(null);
        $this->assertTrue($response);
    }
}
