<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

trait TranslatableAttributesTrait
{
    /**
     * @param string $name
     * @param string $languageIso
     *
     * @return TranslatableAttribute|null
     */
    public function findAttribute(string $name, string $languageIso = ''): ?TranslatableAttribute
    {
        /** @var TranslatableAttribute $attribute */
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getName($languageIso) === $name) {
                return $attribute;
            }
        }

        return null;
    }
}
