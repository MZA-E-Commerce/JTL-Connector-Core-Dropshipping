<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ConnectorIdentification extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('endpointVersion')]
    #[Serializer\Accessor(getter: 'getEndpointVersion', setter: 'setEndpointVersion')]
    protected string $endpointVersion = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('platformName')]
    #[Serializer\Accessor(getter: 'getPlatformName', setter: 'setPlatformName')]
    protected string $platformName = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('platformVersion')]
    #[Serializer\Accessor(getter: 'getPlatformVersion', setter: 'setPlatformVersion')]
    protected string $platformVersion = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('protocolVersion')]
    #[Serializer\Accessor(getter: 'getProtocolVersion', setter: 'setProtocolVersion')]
    protected int $protocolVersion = 0;

    #[Serializer\Type(ConnectorServerInfo::class)]
    #[Serializer\SerializedName('serverInfo')]
    #[Serializer\Accessor(getter: 'getServerInfo', setter: 'setServerInfo')]
    protected ?ConnectorServerInfo $serverInfo = null;

    /**
     * @return string
     */
    public function getEndpointVersion(): string
    {
        return $this->endpointVersion;
    }

    /**
     * @param string $endpointVersion
     *
     * @return $this
     */
    public function setEndpointVersion(string $endpointVersion): self
    {
        $this->endpointVersion = $endpointVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatformName(): string
    {
        return $this->platformName;
    }

    /**
     * @param string $platformName
     *
     * @return $this
     */
    public function setPlatformName(string $platformName): self
    {
        $this->platformName = $platformName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatformVersion(): string
    {
        return $this->platformVersion;
    }

    /**
     * @param string $platformVersion
     *
     * @return $this
     */
    public function setPlatformVersion(string $platformVersion): self
    {
        $this->platformVersion = $platformVersion;

        return $this;
    }

    /**
     * @return int
     */
    public function getProtocolVersion(): int
    {
        return $this->protocolVersion;
    }

    /**
     * @param int $protocolVersion
     *
     * @return $this
     */
    public function setProtocolVersion(int $protocolVersion): self
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    /**
     * @return ConnectorServerInfo|null
     */
    public function getServerInfo(): ?ConnectorServerInfo
    {
        return $this->serverInfo;
    }

    /**
     * @param ConnectorServerInfo $connectorServerInfo
     *
     * @return $this
     */
    public function setServerInfo(ConnectorServerInfo $connectorServerInfo): self
    {
        $this->serverInfo = $connectorServerInfo;

        return $this;
    }
}
