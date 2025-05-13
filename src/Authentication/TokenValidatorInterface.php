<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Authentication;

interface TokenValidatorInterface
{
    /**
     * @param string $token
     *
     * @return bool
     */
    public function validate(string $token): bool;
}
