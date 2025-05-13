<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Tax zone model (set in JTL-Wawi ERP).
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class TaxZone extends AbstractIdentity
{
    /** @var string Optional tax zone name e.g. "EU Zone" */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var TaxZoneCountry[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\TaxZoneCountry>')]
    #[Serializer\SerializedName('countries')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $countries = [];

    /**
     * @return string Optional tax zone name e.g. "EU Zone"
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Optional tax zone name e.g. "EU Zone"
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param TaxZoneCountry $country
     *
     * @return $this
     */
    public function addCountry(TaxZoneCountry $country): self
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * @return TaxZoneCountry[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return $this
     */
    public function clearCountries(): self
    {
        $this->countries = [];

        return $this;
    }
}
