<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

interface ItemsInterface
{
    /**
     * @param AbstractModel $item
     *
     * @return $this
     */
    public function addItem(AbstractModel $item): self;

    /**
     * @return AbstractModel[]
     */
    public function getItems(): array;

    /**
     * @param AbstractModel ...$items
     *
     * @return $this
     */
    public function setItems(AbstractModel ...$items): self;

    /**
     * @return $this
     */
    public function clearItems(): self;
}
