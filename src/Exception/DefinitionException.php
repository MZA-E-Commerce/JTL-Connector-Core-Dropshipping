<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

use Jtl\Connector\Core\Definition\ErrorCode;

class DefinitionException extends \Exception
{
    public const MODEL_MAPPING_NOT_EXISTS   = 10,
        UNKNOWN_IDENTITY_PROPERTY           = 20,
        UNKNOWN_MODEL                       = 30,
        IDENTITY_TYPE_MAPPING_NOT_EXISTS    = 40,
        UNKNOWN_IDENTITY_TYPE               = 50,
        UNKNOWN_CONFIG_OPTION               = 70,
        DEFAULT_VALUE_NOT_EXISTS            = 80,
        UNKNOWN_MOMENT                      = 100,
        UNKNOWN_RELATION_TYPE               = 110,
        UNKNOWN_IMAGE_IDENTITY_TYPE_MAPPING = 120,
        UNKNOWN_RPC_METHOD                  = 130,
        RELATION_TYPE_CANNOT_BE_EMPTY       = 140;

    /**
     * @param string $modelName
     *
     * @return self
     */
    public static function modelMappingNotExists(string $modelName): self
    {
        $msg = \sprintf('Identity type for model (%s) not found', $modelName);
        return new self($msg, self::MODEL_MAPPING_NOT_EXISTS);
    }

    /**
     * @param int $identityType
     *
     * @return self
     */
    public static function identityTypeMappingNotExists(int $identityType): self
    {
        $msg = \sprintf('Model for identity type (%s) not found', $identityType);
        return new self($msg, self::IDENTITY_TYPE_MAPPING_NOT_EXISTS);
    }

    /**
     * @param int $identityType
     *
     * @return self
     */
    public static function unknownIdentityType(int $identityType): self
    {
        $msg = \sprintf('Identity type (%s) does not exist', $identityType);
        return new self($msg, self::UNKNOWN_IDENTITY_TYPE);
    }

    /**
     * @param string $modelName
     * @param string $propertyName
     *
     * @return self
     */
    public static function unknownIdentityProperty(string $modelName, string $propertyName): self
    {
        $msg = \sprintf('Unknown identity property (%s) in model (%s)', $propertyName, $modelName);
        return new self($msg, self::UNKNOWN_IDENTITY_PROPERTY);
    }

    /**
     * @param string $modelName
     *
     * @return self
     */
    public static function unknownModel(string $modelName): self
    {
        $msg = \sprintf('Model (%s) does not exist', $modelName);
        return new self($msg, self::UNKNOWN_MODEL);
    }

    /**
     * @param string $controllerName
     *
     * @return self
     */
    public static function unknownController(string $controllerName): self
    {
        $msg = \sprintf('Unknown controller (%s)', $controllerName);
        return new self($msg, ErrorCode::UNKNOWN_CONTROLLER);
    }

    /**
     * @param string $action
     *
     * @return self
     */
    public static function unknownAction(string $action): self
    {
        $msg = \sprintf('Unknown action (%s)', $action);
        return new self($msg, ErrorCode::UNKNOWN_ACTION);
    }

    /**
     * @param string $moment
     *
     * @return self
     */
    public static function unknownMoment(string $moment): self
    {
        $msg = \sprintf('Unknown moment (%s)', $moment);
        return new self($msg, self::UNKNOWN_MOMENT);
    }

    /**
     * @param string $relationType
     *
     * @return self
     */
    public static function unknownRelationType(string $relationType): self
    {
        $msg = \sprintf('Unknown relation type (%s)', $relationType);
        return new self($msg, self::UNKNOWN_RELATION_TYPE);
    }

    /**
     * @return self
     */
    public static function relationTypeCannotBeEmpty(): self
    {
        return new self('Relation type cannot be empty.', self::RELATION_TYPE_CANNOT_BE_EMPTY);
    }

    /**
     * @param string $rpcMethod
     *
     * @return self
     */
    public static function unknownRpcMethod(string $rpcMethod): self
    {
        $msg = \sprintf('Unknown rpc method (%s)', $rpcMethod);
        return new self($msg, self::UNKNOWN_RPC_METHOD);
    }
}
