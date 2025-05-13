<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Rpc;

use Doctrine\Common\Annotations\AnnotationException;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Exception\InvalidArgumentException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer as JmsSerializer;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Serializer\SerializerBuilder;

/**
 * Rpc Packet
 *
 * @access public
 */
abstract class Packet extends AbstractModel
{
    /**
     * A String specifying the version of the JSON-RPC protocol.
     * MUST be exactly "2.0".
     */
    #[Serializer\Type('string')]
    protected string $jtlrpc = '';

    #[Serializer\Type('string')]
    protected string $id = '';

    /**
     * Packet constructor.
     */
    final public function __construct()
    {
    }

    /**
     * @return bool
     */
    abstract public function isValid(): bool;


    /**
     * Getter for $jtlrpc
     *
     * @return string
     */
    public function getJtlrpc(): string
    {
        return $this->jtlrpc;
    }

    /**
     * Setter for $jtlrpc
     *
     * @param string $jtlrpc
     *
     * @return $this
     */
    public function setJtlrpc(string $jtlrpc): self
    {
        $this->jtlrpc = $jtlrpc;

        return $this;
    }

    /**
     * Getter for $id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Setter for $id
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @param JmsSerializer|null $serializer
     *
     * @return mixed[]
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     */
    public function toArray(?JmsSerializer $serializer = null): array
    {
        if (\is_null($serializer)) {
            $serializer = SerializerBuilder::create()->build();
        }

        $context = (new SerializationContext())->setSerializeNull(true);

        return $serializer->toArray($this, $context);
    }

    /**
     * @param string $id
     * @param string $jtlrpc
     *
     * @return self
     */
    public static function create(string $id, string $jtlrpc = '2.0'): self
    {
        return (new static())->setId($id)->setJtlrpc($jtlrpc);
    }
}
