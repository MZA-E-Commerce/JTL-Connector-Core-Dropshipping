<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

abstract class AbstractI18nFactory extends AbstractModelFactory
{
    /** @var array<string> */
    protected array $usedLanguages = [];

    /**
     * @param array<mixed> $override
     *
     * @return array<string, string>
     */
    public function makeOneArray(array $override = []): array
    {
        $languageIsoAppendix = [];
        if (!isset($override['languageIso'])) {
            while (true) {
                if (\count($this->usedLanguages) > 180) {
                    $this->clearUsedLanguages();
                }

                $languageIso = $this->faker->languageCode;
                if (!\in_array($languageIso, $this->usedLanguages, true)) {
                    $languageIsoAppendix['languageIso'] = $languageIso;
                    $this->usedLanguages[]              = $languageIso;
                    break;
                }
            }
        }

        /** @var array<string, string> $makeArray */
        $makeArray = parent::makeOneArray($override);
        return $makeArray + $languageIsoAppendix;
    }

    /**
     * @return void
     */
    public function clearUsedLanguages(): void
    {
        $this->usedLanguages = [];
    }

    /**
     * @param int          $quantity
     * @param array<mixed> $specificOverrides
     * @param array<mixed> $globalOverrides
     *
     * @return array<int, array<string, string>>
     */
    public function makeArray(int $quantity, array $specificOverrides = [], array $globalOverrides = []): array
    {
        $usedLanguagesCount = \count($this->usedLanguages);
        if ($quantity >= $usedLanguagesCount || $usedLanguagesCount - $quantity > 180) {
            $this->clearUsedLanguages();
        }

        /** @var array<int, array<string, string>> $makeArray */
        $makeArray = parent::makeArray($quantity, $specificOverrides, $globalOverrides);

        return $makeArray;
    }
}
