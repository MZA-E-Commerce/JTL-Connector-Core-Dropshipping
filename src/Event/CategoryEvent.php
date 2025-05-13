<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\Category;
use Symfony\Contracts\EventDispatcher\Event;

class CategoryEvent extends Event
{
    protected Category $category;

    /**
     * CategoryEvent constructor.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}
