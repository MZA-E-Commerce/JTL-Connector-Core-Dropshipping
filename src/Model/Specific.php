<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Specific is defined as a characteristic product attribute Like "color".
 * Specifics can be used for after-search-filtering.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Specific extends AbstractIdentity
{
    public const
        TYPE_TEXT       = 'TEXT',
        TYPE_SELECT     = 'SELECTBOX',
        TYPE_IMAGE      = 'BILD',
        TYPE_IMAGE_TEXT = 'BILD-TEXT';

    /**
     * @var bool Optional: Global specific means the specific can be used like a category
     *              (e.g. show all red products in shop)
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isGlobal')]
    #[Serializer\Accessor(getter: 'getIsGlobal', setter: 'setIsGlobal')]
    protected bool $isGlobal = false;

    /**  @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 1;

    /** @var string Specific type (radio, dropdown, image...) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected string $type = '';

    /** @var SpecificI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\SpecificI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /** @var SpecificValue[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\SpecificValue>')]
    #[Serializer\SerializedName('values')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $values = [];

    /**
     * @return bool Optional: Global specific means the specific can be used like a category
     *                  (e.g. show all red products in shop)
     */
    public function getIsGlobal(): bool
    {
        return $this->isGlobal;
    }

    /**
     * @param bool $isGlobal Optional: Global specific means the specific can be used like a category
     *                          (e.g. show all red products in shop)
     *
     * @return $this
     */
    public function setIsGlobal(bool $isGlobal): self
    {
        $this->isGlobal = $isGlobal;

        return $this;
    }

    /**
     * @return int Optional sort number
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort number
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string Specific type (radio, dropdown, image...)
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type Specific type (radio, dropdown, image...)
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param SpecificI18n $i18n
     *
     * @return $this
     */
    public function addI18n(SpecificI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

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

    /**
     * @param SpecificValue $value
     *
     * @return $this
     */
    public function addValue(SpecificValue $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * @return SpecificValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param SpecificValue ...$values
     *
     * @return $this
     */
    public function setValues(SpecificValue ...$values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearValues(): self
    {
        $this->values = [];

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIdentificationStrings(): array
    {
        $this->unsetIdentificationStringBySubject('name');
        foreach ($this->getI18ns() as $i18n) {
            if ($i18n->getName() !== '') {
                $this->setIdentificationStringBySubject('name', \sprintf('Name = %s', $i18n->getName()));
                break;
            }
        }

        return parent::getIdentificationStrings();
    }

    /**
     * @return SpecificI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param SpecificI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(SpecificI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }
}
