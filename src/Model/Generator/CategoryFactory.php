<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\Category;

/**
 * Class CategoryFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CategoryFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        $identityFactory = $this->getFactory('Identity');

        return [
            'level'            => $this->faker->numberBetween(0, 10),
            'parentCategoryId' => $identityFactory->makeOneArray(),
            'sort'             => $this->faker->numberBetween(0, 10),
            'id'               => $identityFactory->makeOneArray(),
            'isActive'         => $this->faker->boolean,
            'attributes'       => $this->getFactory('TranslatableAttribute')->makeArray(\random_int(1, 3)),
            'customerGroups'   => $this->getFactory('CategoryCustomerGroup')->makeArray(\random_int(1, 3)),
            'invisibilities'   => $this->getFactory('CategoryInvisibility')->makeArray(\random_int(1, 3)),
            'i18ns'            => $this->getFactory('CategoryI18n')->makeArray(\random_int(1, 3)),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Category::class;
    }
}
