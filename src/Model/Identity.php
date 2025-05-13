<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;

/**
 * Identity
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Internal
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Identity extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('endpoint')]
    #[Serializer\Accessor(getter: 'getEndpoint', setter: 'setEndpoint')]
    protected string $endpoint = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('host')]
    #[Serializer\Accessor(getter: 'getHost', setter: 'setHost')]
    protected int $host = 0;

    /**
     * Constructor
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        $this->endpoint = $endpoint;
        $this->host     = $host;
    }

    /**
     * Convert from Array
     *
     * @param array<int, string|int> $data
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        if (
            !isset($data[0], $data[1]) || \count($data) !== 2 || !\is_string($data[0]) || !\is_int($data[1])
        ) {
            throw new InvalidArgumentException(
                'The argument is not valid. 
                It must consist of exactly two fields.
                First field (0) must contain a string value and second field (1) must contain an integer value'
            );
        }

        return new self($data[0], $data[1]);
    }

    /**
     * Gets the value of endpoint.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Sets the value of endpoint.
     *
     * @param string $endpoint the endpoint
     *
     * @return $this
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Gets the value of host.
     *
     * @return int
     */
    public function getHost(): int
    {
        return $this->host;
    }

    /**
     * Sets the value of host.
     *
     * @param int $host the host
     *
     * @return $this
     */
    public function setHost(int $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Convert to Array
     *
     * @return array{0: string, 1: int}
     */
    public function toArray(): array
    {
        return [
            $this->endpoint,
            $this->host,
        ];
    }
}
