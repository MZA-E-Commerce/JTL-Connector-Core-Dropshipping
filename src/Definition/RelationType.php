<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

use Jtl\Connector\Core\Exception\DefinitionException;

final class RelationType
{
    /**
     * @param string $relationType
     *
     * @return int
     * @throws DefinitionException
     */
    public static function getRelatedImageIdentityType(string $relationType): int
    {
        if (!self::hasRelatedImageIdentityType($relationType)) {
            throw DefinitionException::unknownRelationType(self::getRelatedImageModelName($relationType));
        }

        return Model::getIdentityType(self::getRelatedImageModelName($relationType));
    }

    /**
     * @param string $relationType
     *
     * @return bool
     * @throws DefinitionException
     */
    public static function hasRelatedImageIdentityType(string $relationType): bool
    {
        return Model::hasIdentityType(self::getRelatedImageModelName($relationType));
    }

    /**
     * @param string $relationType
     *
     * @return bool
     */
    public static function hasIdentityType(string $relationType): bool
    {
        return Model::hasIdentityType(\ucfirst($relationType));
    }

    /**
     * @param string $relationType
     *
     * @return string
     * @throws DefinitionException
     */
    public static function getRelatedImageModelName(string $relationType): string
    {
        return \sprintf('%sImage', self::getModelName($relationType));
    }

    /**
     * @param string $relationType
     *
     * @return string
     * @throws DefinitionException
     */
    public static function getModelName(string $relationType): string
    {
        if (!self::hasIdentityType($relationType)) {
            throw DefinitionException::unknownRelationType($relationType);
        }

        return \ucfirst($relationType);
    }

    /**
     * @param string $relationType
     *
     * @return int
     * @throws DefinitionException
     */
    public static function getIdentityType(string $relationType): int
    {
        if (!self::hasIdentityType($relationType)) {
            throw DefinitionException::unknownRelationType($relationType);
        }

        return Model::getIdentityType(self::getModelName($relationType));
    }

    /**
     * @param string $relationType
     *
     * @return bool
     */
    public static function isRelationType(string $relationType): bool
    {
        return self::hasIdentityType($relationType);
    }
}
