<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ImageI18n extends AbstractI18n implements IdentityInterface
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('id')]
    #[Serializer\Accessor(getter: 'getId', setter: 'setId')]
    protected Identity $id;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('altText')]
    #[Serializer\Accessor(getter: 'getAltText', setter: 'setAltText')]
    protected string $altText = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = new Identity();
    }

    /**
     * @inheritDoc
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->id);
    }

    /**
     * @inheritDoc
     */
    public function setId(Identity $identity): self
    {
        $this->id = $identity;

        return $this;
    }

    /**
     * @return string
     */
    public function getAltText(): string
    {
        return $this->altText;
    }

    /**
     * @param string $altText
     *
     * @return $this
     */
    public function setAltText(string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }
}
