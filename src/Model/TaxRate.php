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
class TaxRate extends AbstractIdentity
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('countryIso')]
    #[Serializer\Accessor(getter: 'getCountryIso', setter: 'setCountryIso')]
    protected string $countryIso = '';


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('rate')]
    #[Serializer\Accessor(getter: 'getRate', setter: 'setRate')]
    protected float $rate = 0.0;

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return $this
     */
    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryIso(): string
    {
        return $this->countryIso;
    }

    /**
     * @param string $countryIso
     *
     * @return $this
     */
    public function setCountryIso(string $countryIso): self
    {
        $this->countryIso = $countryIso;

        return $this;
    }
}
