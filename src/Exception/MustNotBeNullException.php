<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

use Throwable;

class MustNotBeNullException extends \RuntimeException
{
    /**
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string    $message = 'variable must not be null.',
        int       $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $varName
     *
     * @return self
     */
    public static function fromVarName(string $varName): self
    {
        return new self($varName . ' must not be null!');
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public static function fromExpectedType(string $type): self
    {
        return new self($type . ' expected, but null given.');
    }
}
