<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Connector;

use Jtl\Connector\Core\Checksum\ChecksumLoaderInterface;

/**
 * Interface ChecksumInterface
 *
 * @package Jtl\Connector\Core\Checksum
 */
interface UseChecksumInterface
{
    /**
     * @return ChecksumLoaderInterface
     */
    public function getChecksumLoader(): ChecksumLoaderInterface;
}
