<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Error;

use Jtl\Connector\Core\Http\JsonResponse;
use Jtl\Connector\Core\Rpc\Error;
use Jtl\Connector\Core\Rpc\RequestPacket;
use Jtl\Connector\Core\Rpc\ResponsePacket;
use Jtl\Connector\Core\Utilities\Validator\Validate;

class ErrorHandler extends AbstractErrorHandler
{
    /** @var int[] */
    protected static array $shutdownHandleErrors = [
        \E_ERROR,
        \E_CORE_ERROR,
        \E_USER_ERROR,
        \E_RECOVERABLE_ERROR,
        \E_COMPILE_ERROR,
        \E_PARSE,
    ];

    protected JsonResponse $response;

    /**
     * ErrorHandler constructor.
     *
     * @param JsonResponse $response
     */
    public function __construct(JsonResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return callable
     */
    public function getExceptionHandler(): callable
    {
        return static function (\Throwable $ex) {
            return null;
        };
    }

    /**
     * @return callable
     */
    public function getErrorHandler(): callable
    {
        return static function ($errno, $errstr, $errfile = '', $errline = -1, $errcontext = null) {
            return !\in_array($errno, static::$shutdownHandleErrors, true);
        };
    }

    /**
     * @return callable
     */
    public function getShutdownHandler(): callable
    {
        return function (): void {
            if (($err = \error_get_last()) && \in_array($err['type'], static::$shutdownHandleErrors, true)) {
                \ob_clean();

                if (($offset = \strrpos($err['file'], '/')) === false) {
                    throw new \RuntimeException('$offset must be an integer.');
                }
                $file = \sprintf('...%s', \substr($err['file'], $offset));

                $error = new Error();
                $error->setCode($err['type'])
                      ->setData('Shutdown! File: ' . $file . ' - Line: ' . $err['line'])
                      ->setMessage($err['message']);

                $requestPacket = Validate::requestPacket(RequestPacket::create('unknown'));
                $requestPacket->setMethod('unknown.unknown');
                $requestPacket = $this->requestPacket;

                $responsePacket = Validate::responsePacket(
                    ResponsePacket::create(
                        $requestPacket->getId(),
                        $requestPacket->getJtlrpc()
                    )
                );
                $responsePacket->setError($error);

                $this->response->prepareAndSend($requestPacket, $responsePacket);
            }
        };
    }
}
