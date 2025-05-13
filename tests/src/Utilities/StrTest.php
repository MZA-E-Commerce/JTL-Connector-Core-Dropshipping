<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Utilities;

use Jtl\Connector\Core\Utilities\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class StrTest
 *
 * @package Jtl\Connector\Core\Test\Utilities
 */
class StrTest extends TestCase
{
    /**
     * @dataProvider toCamelCaseDataProvider
     *
     * @param string $data
     * @param string $expectedResult
     *
     * @return void
     * @throws \Exception
     */
    public function testToCamelCase(string $data, string $expectedResult): void
    {
        $this->assertSame($expectedResult, Str::toCamelCase($data));
    }

    /**
     * @return array<int, array{0: string, 1: string}>
     */
    public function toCamelCaseDataProvider(): array
    {
        return [
            ['snake_case', 'snakeCase',],
            ['PascalCase', 'pascalCase',],
            ['camelCase', 'camelCase',],
            ['false', 'false',],
            ['', '',],
            ['_snake_case_', 'snakeCase',],
            ['snake__case', 'snakeCase',],
            ['12345', '12345',],
            ['    ', '',],
            ['sna\\ke', 'sna\\ke',],
        ];
    }

    /**
     * @dataProvider toPascalCaseDataProvider
     *
     * @param string $data
     * @param string $expectedResult
     *
     * @return void
     * @throws \Exception
     */
    public function testPascalCase(string $data, string $expectedResult): void
    {
        $this->assertSame($expectedResult, Str::toPascalCase($data));
    }

    /**
     * @return array<int, array{0: string|false|int, 1: string}>
     */
    public function toPascalCaseDataProvider(): array
    {
        return [
            ['snake_case', 'SnakeCase',],
            ['camelCase', 'CamelCase',],
            ['PascalCase', 'PascalCase',],
            ['false', 'False',],
            ['', '',],
            ['_snake_case_', 'SnakeCase',],
            ['snake__case', 'SnakeCase',],
            ['12345', '12345',],
            ['    ', '',],
            ['sna\\ke', 'Sna\\Ke',],
        ];
    }

    /**
     * @dataProvider toSnakeCaseDataProvider
     *
     * @param string $data
     * @param string $expectedResult
     *
     * @return void
     * @throws \Exception
     */
    public function testSnakeCase(string $data, string $expectedResult): void
    {
        $this->assertSame($expectedResult, Str::toSnakeCase($data));
    }

    /**
     * @return array<int, array{0: string|false|int, 1: string}>
     */
    public function toSnakeCaseDataProvider(): array
    {
        return [
            ['snake_case', 'snake_case',],
            ['camelCase', 'camel_case',],
            ['PascalCase', 'pascal_case',],
            ['false', 'false',],
            ['', '',],
            ['_snake_case_', 'snake_case',],
            ['snake__case', 'snake_case',],
            ['12345', '12345',],
            ['    ', '',],
            ['sna\\ke', 'sna\\ke',],
        ];
    }
}
