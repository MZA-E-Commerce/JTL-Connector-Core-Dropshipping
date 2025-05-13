<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Currency model properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Currency extends AbstractIdentity
{
    /**
     * @var string Optional delimiter char for cent, default=",".
     *              Ignore this flag if you have the correct user locale preference.
     */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('delimiterCent')]
    #[Serializer\Accessor(getter: 'getDelimiterCent', setter: 'setDelimiterCent')]
    protected string $delimiterCent = '';

    /**
     * @var string Optional delimiter char for thousand. Default=".".
     *              Ignore this flag if you have the correct user locale preference.
     */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('delimiterThousand')]
    #[Serializer\Accessor(getter: 'getDelimiterThousand', setter: 'setDelimiterThousand')]
    protected string $delimiterThousand = '';

    /** @var double Optional conversion factor to default currency. Default is 1 (equals default currency) */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('factor')]
    #[Serializer\Accessor(getter: 'getFactor', setter: 'setFactor')]
    protected float $factor = 0.0;

    /**
     * @var bool Optional: Display currency before or after value.
     *                  Ignore this flag if you have the correct user locale preference.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('hasCurrencySignBeforeValue')]
    #[Serializer\Accessor(getter: 'getHasCurrencySignBeforeValue', setter: 'setHasCurrencySignBeforeValue')]
    protected bool $hasCurrencySignBeforeValue = false;

    /**
     * @var bool Optional: Flag default currency. True, if this is the default currency.
     *                  Exact one currency must be marked as default.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isDefault')]
    #[Serializer\Accessor(getter: 'getIsDefault', setter: 'setIsDefault')]
    protected bool $isDefault = false;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('iso')]
    #[Serializer\Accessor(getter: 'getIso', setter: 'setIso')]
    protected string $iso = '';

    /** @var string Currency name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var string Optional HTML name e.g. "&euro;" */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('nameHtml')]
    #[Serializer\Accessor(getter: 'getNameHtml', setter: 'setNameHtml')]
    protected string $nameHtml = '';

    /**
     * @return string Optional delimiter char for cent, default=",".
     *                  Ignore this flag if you have the correct user locale preference.
     */
    public function getDelimiterCent(): string
    {
        return $this->delimiterCent;
    }

    /**
     * @param string $delimiterCent Optional delimiter char for cent, default=",".
     *                              Ignore this flag if you have the correct user locale preference.
     *
     * @return $this
     */
    public function setDelimiterCent(string $delimiterCent): self
    {
        $this->delimiterCent = $delimiterCent;

        return $this;
    }

    /**
     * @return string Optional delimiter char for thousand. Default=".".
     *                  Ignore this flag if you have the correct user locale preference.
     */
    public function getDelimiterThousand(): string
    {
        return $this->delimiterThousand;
    }

    /**
     * @param string $delimiterThousand Optional delimiter char for thousand. Default=".".
     *                                  Ignore this flag if you have the correct user locale preference.
     *
     * @return $this
     */
    public function setDelimiterThousand(string $delimiterThousand): self
    {
        $this->delimiterThousand = $delimiterThousand;

        return $this;
    }

    /**
     * @return float Optional conversion factor to default currency. Default is 1 (equals default currency)
     */
    public function getFactor(): float
    {
        return $this->factor;
    }

    /**
     * @param float $factor Optional conversion factor to default currency. Default is 1 (equals default currency)
     *
     * @return $this
     */
    public function setFactor(float $factor): self
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * @return bool Optional: Display currency before or after value.
     *                  Ignore this flag if you have the correct user locale preference.
     */
    public function getHasCurrencySignBeforeValue(): bool
    {
        return $this->hasCurrencySignBeforeValue;
    }

    /**
     * @param bool $hasCurrencySignBeforeValue Optional: Display currency before or after value.
     *                                         Ignore this flag if you have the correct user locale preference.
     *
     * @return $this
     */
    public function setHasCurrencySignBeforeValue(bool $hasCurrencySignBeforeValue): self
    {
        $this->hasCurrencySignBeforeValue = $hasCurrencySignBeforeValue;

        return $this;
    }

    /**
     * @return bool Optional: Flag default currency. True, if this is the default currency.
     *                  Exact one currency must be marked as default.
     */
    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault Optional: Flag default currency. True, if this is the default currency.
     *                        Exact one currency must be marked as default.
     *
     * @return $this
     */
    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return string
     */
    public function getIso(): string
    {
        return $this->iso;
    }

    /**
     * @param string $iso
     *
     * @return $this
     */
    public function setIso(string $iso): self
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * @return string Currency name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Currency name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string Optional HTML name e.g. "&euro;"
     */
    public function getNameHtml(): string
    {
        return $this->nameHtml;
    }

    /**
     * @param string $nameHtml Optional HTML name e.g. "&euro;"
     *
     * @return $this
     */
    public function setNameHtml(string $nameHtml): self
    {
        $this->nameHtml = $nameHtml;

        return $this;
    }
}
