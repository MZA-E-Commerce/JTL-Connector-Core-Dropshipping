<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * locale specifig productVariationValue name.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductVariationValueI18n extends AbstractI18n
{
    /** @var string Locale specific variationValue name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string Locale specific variationValue name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Locale specific variationValue name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
