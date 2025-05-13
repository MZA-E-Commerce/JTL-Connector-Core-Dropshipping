<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Locale specific mediafile name + description.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductMediaFileI18n extends AbstractI18n
{
    /** @var string Locale specific description */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('description')]
    #[Serializer\Accessor(getter: 'getDescription', setter: 'setDescription')]
    protected string $description = '';

    /** @var string Locale specific name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string Locale specific description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Locale specific description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string Locale specific name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Locale specific name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
