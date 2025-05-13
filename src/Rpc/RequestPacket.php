<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Rpc;

use Doctrine\Common\Annotations\AnnotationException;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Exception\InvalidArgumentException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\Serializer as JmsSerializer;
use Jtl\Connector\Core\Definition\RpcMethod;
use Jtl\Connector\Core\Serializer\SerializerBuilder;
use RuntimeException;

class RequestPacket extends Packet
{
    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period character
     * (U+002E or ASCII 46)
     * are reserved for rpc-internal methods and extensions and MUST NOT be used
     * for anything else.
     */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('method')]
    #[Serializer\Accessor(getter: 'getMethod', setter: 'setMethod')]
    protected string $method = 'undefined.undefined';

    /**
     * A Structured value that holds the parameter values to be used during the
     * invocation of the method.
     * This member MAY be omitted.
     *
     * @var mixed[]
     */
    #[Serializer\Type('array')]
    #[Serializer\SerializedName('params')]
    #[Serializer\Accessor(getter: 'getParams', setter: 'setParams')]
    protected array $params = [];

    /**
     * @param string             $jtlrpc
     * @param JmsSerializer|null $serializer
     *
     * @return RequestPacket
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws \JMS\Serializer\Exception\RuntimeException
     * @throws UnsupportedFormatException
     */
    public static function createFromJtlrpc(string $jtlrpc, ?JmsSerializer $serializer = null): RequestPacket
    {
        if (\is_null($serializer)) {
            $serializer = SerializerBuilder::create()->build();
        }

        if ($jtlrpc !== '') {
            $packet = $serializer->deserialize($jtlrpc, __CLASS__, 'json');
            if (!($packet instanceof self)) {
                throw new \RuntimeException('deserialized Json must be an instance of RequestPacket.');
            }

            return $packet;
        }

        return new static();
    }

    /**
     * @return mixed[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param mixed[] $params
     *
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $isValid = true;

        // JSON-RPC protocol
        if ($this->getJtlrpc() !== '2.0') {
            $isValid = false;
        }

        // A String containing the name of the method to be invoked
        if (!RpcMethod::isMethod($this->getMethod())) {
            $isValid = false;
        }

        // An identifier established by the Client that MUST contain a
        // String, Number, or NULL value if included
        if ($this->getId() === '') {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
