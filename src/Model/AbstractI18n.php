<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class AbstractI18n
 *
 * @access  public
 * @package Jtl\Connector\Core\Model\Common
 */
#[Serializer\AccessType(['value' => 'public_method'])]
abstract class AbstractI18n extends AbstractModel implements I18nInterface
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('languageIso')]
    #[Serializer\Accessor(getter: 'getLanguageIso', setter: 'setLanguageIso')]
    protected string $languageIso = '';

    /**
     * @return string
     */
    public function getLanguageIso(): string
    {
        return $this->languageIso;
    }

    /**
     * @param string $languageIso
     *
     * @return $this
     */
    public function setLanguageIso(string $languageIso): self
    {
        $this->languageIso = $languageIso;

        return $this;
    }
}
