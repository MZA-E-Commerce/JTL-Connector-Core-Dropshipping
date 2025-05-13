<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

interface TranslatableAttributesInterface
{
    /**
     * @return array<TranslatableAttribute>
     */
    public function getAttributes(): array;

    /**
     * @param TranslatableAttribute ...$attributes
     *
     * @return $this
     */
    public function setAttributes(TranslatableAttribute ...$attributes): self;

    /**
     * @param string $name
     *
     * @return TranslatableAttribute|null
     */
    public function findAttribute(string $name): ?TranslatableAttribute;
}
