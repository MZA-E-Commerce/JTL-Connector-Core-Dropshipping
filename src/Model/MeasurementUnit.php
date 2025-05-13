<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Specifies product units like "ml", "l", " cm".
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class MeasurementUnit extends AbstractIdentity
{
    /** @var string Optional UCUM-Code, see  http://unitsofmeasure.org/ */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('code')]
    #[Serializer\Accessor(getter: 'getCode', setter: 'setCode')]
    protected string $code = '';

    /** @var string Synonym e.g. 'ml' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('displayCode')]
    #[Serializer\Accessor(getter: 'getDisplayCode', setter: 'setDisplayCode')]
    protected string $displayCode = '';

    /** @var MeasurementUnitI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\MeasurementUnitI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return string Optional UCUM-Code, see  http://unitsofmeasure.org/
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code Optional UCUM-Code, see  http://unitsofmeasure.org/
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string Synonym e.g. 'ml'
     */
    public function getDisplayCode(): string
    {
        return $this->displayCode;
    }

    /**
     * @param string $displayCode Synonym e.g. 'ml'
     *
     * @return $this
     */
    public function setDisplayCode(string $displayCode): self
    {
        $this->displayCode = $displayCode;

        return $this;
    }

    /**
     * @param MeasurementUnitI18n $i18n
     *
     * @return $this
     */
    public function addI18n(MeasurementUnitI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return MeasurementUnitI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param MeasurementUnitI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(MeasurementUnitI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearI18ns(): self
    {
        $this->i18ns = [];

        return $this;
    }
}
