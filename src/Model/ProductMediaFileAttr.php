<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Monolingual mediafile attribute.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductMediaFileAttr extends AbstractModel
{
    /** @var ProductMediaFileAttrI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductMediaFileAttrI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @param ProductMediaFileAttrI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductMediaFileAttrI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ProductMediaFileAttrI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductMediaFileAttrI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductMediaFileAttrI18n ...$i18ns): self
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
