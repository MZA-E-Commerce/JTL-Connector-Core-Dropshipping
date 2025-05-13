<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\DeserializationContext as Context;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface as Visitor;

/**
 * Class ObjectConstructor
 *
 * @package Jtl\Connector\Core\Serializer
 */
class ObjectConstructor implements ObjectConstructorInterface
{
    /**
     * @param Visitor                                   $visitor
     * @param ClassMetadata                             $metadata
     * @param mixed                                     $data
     * @param array{name: string, params: array<mixed>} $type
     * @param Context                                   $context
     *
     * @return object|null
     */
    public function construct(
        Visitor       $visitor,
        ClassMetadata $metadata,
        mixed         $data,
        array         $type,
        Context       $context
    ): ?object {
        $className = $metadata->name;

        return new $className();
    }
}
