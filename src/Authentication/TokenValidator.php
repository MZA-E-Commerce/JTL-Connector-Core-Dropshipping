<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Authentication;

use Jtl\Connector\Core\Exception\TokenValidatorException;

/**
 * Class TokenValidator
 *
 * @package Jtl\Connector\Core\Authentication
 */
class TokenValidator implements TokenValidatorInterface
{
    protected string $token;

    /**
     * TokenValidator constructor.
     *
     * @param string $token
     *
     * @throws TokenValidatorException
     */
    public function __construct(string $token)
    {
        if ($token === '') {
            throw TokenValidatorException::emptyToken();
        }
        $this->token = $token;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function validate(string $token): bool
    {
        return $this->token === $token;
    }
}
