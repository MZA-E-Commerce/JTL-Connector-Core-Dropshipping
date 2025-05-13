<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Rpc;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\RpcException;

/**
 * Rpc Error
 *
 * @access public
 */
class Error
{
    /**
     * A Number that indicates the error type that occurred.
     *
     * This MUST be an integer.
     */
    #[Serializer\Type('integer')]
    public int $code = 0;

    /**
     * A String providing a short description of the error.
     *
     * The message SHOULD be limited to a concise single sentence.
     */
    #[Serializer\Type('string')]
    public string $message = '';

    /**
     * A Primitive or Structured value that contains additional information
     * about the error.
     *
     * This may be omitted. The value of this member is defined by the Server
     * (e.g. detailed error information, nested errors etc.).
     */
    public mixed $data = null;

    /**
     * @param \Throwable  $exception
     * @param string|null $additionalMessage
     *
     * @return string
     * @throws \RuntimeException
     */
    public static function createDataFromException(\Throwable $exception, ?string $additionalMessage = null): string
    {
        $lastSlashPos = \strrpos($exception->getFile(), '/');
        if ($lastSlashPos === false) {
            throw new \RuntimeException('error while parsing...');
        }

        $file = \sprintf('...%s', \substr($exception->getFile(), $lastSlashPos));

        $data = \sprintf(
            '%s (Code: %s) in %s:%s',
            \get_class($exception),
            $exception->getCode(),
            $file,
            $exception->getLine()
        );

        if (\is_string($additionalMessage)) {
            $data .= \sprintf(' - %s', $additionalMessage);
        }

        return $data;
    }

    /**
     * Getter for $code
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Setter for $code
     *
     * @param int $code
     *
     * @return $this
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Getter for $data
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Setter for $data
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validates a Rpc Error
     *
     * @return void
     * @throws RpcException
     */
    final public function validate(): void
    {
        $isValid = true;

        // A String providing a short description of the error. The message
        // SHOULD be limited to a concise single sentence.
        if ($this->getMessage() === '') {
            $isValid = false;
        }

        if (!$isValid) {
            throw RpcException::parseError();
        }
    }

    /**
     * Getter for $message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Setter for $message
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
