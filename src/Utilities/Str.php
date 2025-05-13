<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Utilities;

use Jawira\CaseConverter\CaseConverterException;
use Jawira\CaseConverter\Convert;

class Str
{
    /**
     * @param string $string
     *
     * @return string
     * @throws CaseConverterException
     */
    public static function toCamelCase(string $string): string
    {
        return (new Convert($string))->toCamel();
    }

    /**
     * @param string $string
     *
     * @return string
     * @throws CaseConverterException
     */
    public static function toPascalCase(string $string): string
    {
        return (new Convert($string))->toPascal();
    }

    /**
     * @param string $string
     *
     * @return string
     * @throws CaseConverterException
     */
    public static function toSnakeCase(string $string): string
    {
        return (new Convert($string))->toSnake();
    }
}
