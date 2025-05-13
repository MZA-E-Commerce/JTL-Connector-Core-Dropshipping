<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Rpc;

class Warnings
{
    /** @var array<int, string> */
    protected array $warnings = [];

    /**
     * @return bool
     */
    public function hasWarnings(): bool
    {
        if (\count($this->warnings) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function addWarning(string $message): self
    {
        $this->warnings[] = $message;
        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}
