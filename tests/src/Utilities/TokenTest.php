<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Utilities;

use Jtl\Connector\Core\Utilities\Token;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenTest
 *
 * @package Jtl\Connector\Core\Test\Utilities
 */
class TokenTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGenerateToken(): void
    {
        $token = Token::generate();
        $this->assertEquals(36, \strlen($token));

        $uuidV4Pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/';

        $match = \preg_match($uuidV4Pattern, $token);

        $this->assertEquals(1, $match);
    }
}
