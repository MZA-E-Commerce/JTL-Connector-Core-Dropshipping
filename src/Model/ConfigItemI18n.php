<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Localized config item name and description.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ConfigItemI18n extends AbstractI18n
{
    /** @var string Description (html). Will be ignored, if inheritProductName==true */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('description')]
    #[Serializer\Accessor(getter: 'getDescription', setter: 'setDescription')]
    protected string $description = '';

    /** @var string Config item name. Will be ignored if inheritProductName==true */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string Description (html). Will be ignored, if inheritProductName==true
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Description (html). Will be ignored, if inheritProductName==true
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string Config item name. Will be ignored if inheritProductName==true
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Config item name. Will be ignored if inheritProductName==true
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
