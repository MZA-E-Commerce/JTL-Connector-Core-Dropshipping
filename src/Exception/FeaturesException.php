<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class FeaturesException extends \Exception
{
    public const
        ENTITY_NOT_FOUND = 10,
        FLAG_NOT_FOUND   = 20;

    /**
     * @param string $entityName
     *
     * @return self
     */
    public static function entityNotFound(string $entityName): self
    {
        return new self(\sprintf('Entity "%s" not found', $entityName), self::ENTITY_NOT_FOUND);
    }

    /**
     * @param string $flagName
     *
     * @return self
     */
    public static function flagNotFound(string $flagName): self
    {
        return new self(\sprintf('Flag "%s" not found', $flagName), self::FLAG_NOT_FOUND);
    }
}
