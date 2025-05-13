<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * warehouse model (set in JTL-Wawi ERP).
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Warehouse extends AbstractIdentity
{
    /** @var string Warehouse name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string Warehouse name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Warehouse name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
