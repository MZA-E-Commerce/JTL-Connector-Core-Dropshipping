<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Faker\Extension\ExtensionNotFound;
use InvalidArgumentException;
use Jtl\Connector\Core\Model\Identity;

class IdentityFactory extends AbstractModelFactory
{
    /**
     * @param int          $quantity
     * @param array<mixed> $specificOverrides
     * @param array<mixed> $globalOverrides
     *
     * @return array<int, array{0: string, 1: int}>
     */
    public function makeArray(int $quantity, array $specificOverrides = [], array $globalOverrides = []): array
    {
        try {
            $specificOverrides = $this->checkOverrideArray($specificOverrides) ?? [];
            $globalOverrides   = $this->checkOverrideArray($globalOverrides) ?? [];
        } catch (\InvalidArgumentException $ex) {
            [$specificOverrides, $globalOverrides] = [[], []];
        }


        /** @var array<int, array{0: string, 1: int}> $identityArray */
        $identityArray = parent::makeArray($quantity, $specificOverrides, $globalOverrides);

        return $identityArray;
    }

    /**
     * @param array<mixed> $overrides
     *
     * @return array{0?: string, 1?: int}|null
     * @throws \InvalidArgumentException
     */
    private function checkOverrideArray(array $overrides): ?array
    {
        if (\count($overrides) < 1) {
            return null;
        }

        $valid = true;
        foreach ($overrides as $index => $override) {
            if (!\in_array($index, [0, 1], true)) {
                $valid = false;
            }
        }

        if ($valid === true) {
            if (isset($overrides[0]) && !\is_string($overrides[0])) {
                $valid = false;
            }
            if (isset($overrides[1]) && !\is_int($overrides[1])) {
                $valid = false;
            }
        }

        if ($valid === false) {
            throw new \InvalidArgumentException('Overrides are not the correct array format for Identity.');
        }

        /** @var array{0?: string, 1?: int} $overrides */
        return $overrides;
    }

    /**
     * @param array<mixed> $override
     *
     * @return array{0: string, 1: int}
     * @throws InvalidArgumentException
     */
    public function makeOneArray(array $override = []): array
    {
        $this->checkOverrideArray($override);

        /** @var array{0: string, 1: int} $identityArray */
        $identityArray = parent::makeOneArray($override);

        return $identityArray;
    }

    /**
     * @return array{0: string, 1: int}
     * @throws ExtensionNotFound
     */
    protected function makeFakeArray(): array
    {
        return [
            $this->faker->uuid,
            $this->faker->numberBetween(1),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Identity::class;
    }
}
