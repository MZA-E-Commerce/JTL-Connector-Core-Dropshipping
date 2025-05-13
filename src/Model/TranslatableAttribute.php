<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use JsonException;
use Jtl\Connector\Core\Exception\TranslatableAttributeException;

/**
 * TranslatableAttribute class
 *
 * @access  public
 * @package Jtl\Connector\Core\Model\TranslatableAttribute
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class TranslatableAttribute extends AbstractIdentity
{
    public const
        TYPE_BOOL   = 'bool',
        TYPE_FLOAT  = 'float',
        TYPE_INT    = 'int',
        TYPE_JSON   = 'json',
        TYPE_STRING = 'string';

    /** @var string[] */
    #[Serializer\Exclude]
    protected static array $types = [
        self::TYPE_BOOL,
        self::TYPE_FLOAT,
        self::TYPE_INT,
        self::TYPE_JSON,
        self::TYPE_STRING,
    ];

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isTranslated')]
    #[Serializer\Accessor(getter: 'getIsTranslated', setter: 'setIsTranslated')]
    protected bool $isTranslated = false;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isCustomProperty')]
    #[Serializer\Accessor(getter: 'getIsCustomProperty', setter: 'setIsCustomProperty')]
    protected bool $isCustomProperty = false;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected string $type = self::TYPE_STRING;

    /** @var TranslatableAttributeI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\TranslatableAttributeI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return self::$types;
    }

    /**
     * @return bool
     */
    public function getIsTranslated(): bool
    {
        return $this->isTranslated;
    }

    /**
     * @param bool $isTranslated
     *
     * @return $this
     */
    public function setIsTranslated(bool $isTranslated): self
    {
        $this->isTranslated = $isTranslated;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsCustomProperty(): bool
    {
        return $this->isCustomProperty;
    }

    /**
     * @param bool $isCustomProperty
     *
     * @return $this
     */
    public function setIsCustomProperty(bool $isCustomProperty): self
    {
        $this->isCustomProperty = $isCustomProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        if (!self::isType($type)) {
            //Fallback to string if type is not known
            $type = self::TYPE_STRING;
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isType(string $type): bool
    {
        return \in_array($type, self::$types, true);
    }

    /**
     * @return TranslatableAttributeI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param TranslatableAttributeI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(TranslatableAttributeI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }

    /**
     * @param TranslatableAttributeI18n $i18n
     *
     * @return $this
     */
    public function addI18n(TranslatableAttributeI18n $i18n): self
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
     * @param string $languageIso
     *
     * @return string
     */
    public function getName(string $languageIso = ''): string
    {
        $translation = $this->findTranslation($languageIso);
        if ($translation !== null) {
            return $translation->getName();
        }

        return '';
    }

    /**
     * @param string $languageIso
     *
     * @return TranslatableAttributeI18n|null
     */
    public function findTranslation(string $languageIso): ?TranslatableAttributeI18n
    {
        $i18n = null;
        foreach (\array_reverse($this->i18ns) as $i18n) {
            if ($i18n->getLanguageIso() === $languageIso) {
                break;
            }
        }

        return $i18n;
    }

    /**
     * @param string $languageIso
     *
     * @return bool|float|int|string|array<mixed>|null
     * @throws TranslatableAttributeException
     * @throws JsonException
     */
    public function findValue(string $languageIso): array|float|bool|int|string|null
    {
        $i18n = $this->findTranslation($languageIso);
        if ($i18n instanceof TranslatableAttributeI18n) {
            return $i18n->getValue($this->type);
        }

        return null;
    }

    /**
     * @param string|null $type
     *
     * @return array<string, bool|float|int|string|array<mixed>|null>
     * @throws TranslatableAttributeException
     * @throws JsonException
     */
    public function getValues(?string $type = null): array
    {
        $type   = $type ?? $this->type ?? self::TYPE_STRING;
        $values = [];
        foreach ($this->i18ns as $i18n) {
            $values[$i18n->getLanguageIso()] = $i18n->getValue($type);
        }

        return $values;
    }
}
