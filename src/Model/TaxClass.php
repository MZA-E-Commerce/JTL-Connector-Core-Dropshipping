<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Tax class model (set in JTL-Wawi ERP)
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class TaxClass extends AbstractIdentity
{
    /** @var bool Optional: Flag default tax class. Default is false. Exact 1 taxClass has to be marked as default. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isDefault')]
    #[Serializer\Accessor(getter: 'getIsDefault', setter: 'setIsDefault')]
    protected bool $isDefault = false;

    /** @var string Optional tax class name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return bool Optional: Flag default tax class. Default is false. Exact 1 taxClass has to be marked as default.
     */
    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault Optional:
     *                           Flag default tax class. Default is false. Exact 1 taxClass has to be marked as default.
     *
     * @return $this
     */
    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return string Optional tax class name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Optional tax class name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
