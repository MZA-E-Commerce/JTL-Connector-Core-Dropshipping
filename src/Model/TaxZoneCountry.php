<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * TaxZone to Country Allocation (set in JTL-Wawi ERP).
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class TaxZoneCountry extends AbstractModel
{
    /** @var string Country ISO 3166-2 (2 letter Uppercase) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('countryIso')]
    #[Serializer\Accessor(getter: 'getCountryIso', setter: 'setCountryIso')]
    protected string $countryIso = '';

    /**
     * @return string Country ISO 3166-2 (2 letter Uppercase)
     */
    public function getCountryIso(): string
    {
        return $this->countryIso;
    }

    /**
     * @param string $countryIso Country ISO 3166-2 (2 letter Uppercase)
     *
     * @return $this
     */
    public function setCountryIso(string $countryIso): self
    {
        $this->countryIso = $countryIso;

        return $this;
    }
}
