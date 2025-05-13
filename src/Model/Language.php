<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Global language model
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Language extends AbstractI18n implements IdentityInterface
{
    /** @var Identity Unique language id */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('id')]
    #[Serializer\Accessor(getter: 'getId', setter: 'setId')]
    protected Identity $id;

    /** @var bool Flag default language for frontend. Exact 1 language must be marked as default. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isDefault')]
    #[Serializer\Accessor(getter: 'getIsDefault', setter: 'setIsDefault')]
    protected bool $isDefault = false;

    /** @var string English term */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('nameEnglish')]
    #[Serializer\Accessor(getter: 'getNameEnglish', setter: 'setNameEnglish')]
    protected string $nameEnglish = '';

    /** @var string German term */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('nameGerman')]
    #[Serializer\Accessor(getter: 'getNameGerman', setter: 'setNameGerman')]
    protected string $nameGerman = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = new Identity();
    }

    /**
     * @return Identity Unique language id
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->id);
    }

    /**
     * @param Identity $identity Unique language id
     *
     * @return $this
     */
    public function setId(Identity $identity): self
    {
        $this->id = $identity;

        return $this;
    }

    /**
     * @return bool Flag default language for frontend. Exact 1 language must be marked as default.
     */
    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault Flag default language for frontend. Exact 1 language must be marked as default.
     *
     * @return $this
     */
    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return string English term
     */
    public function getNameEnglish(): string
    {
        return $this->nameEnglish;
    }

    /**
     * @param string $nameEnglish English term
     *
     * @return $this
     */
    public function setNameEnglish(string $nameEnglish): self
    {
        $this->nameEnglish = $nameEnglish;

        return $this;
    }

    /**
     * @return string German term
     */
    public function getNameGerman(): string
    {
        return $this->nameGerman;
    }

    /**
     * @param string $nameGerman German term
     *
     * @return $this
     */
    public function setNameGerman(string $nameGerman): self
    {
        $this->nameGerman = $nameGerman;

        return $this;
    }
}
