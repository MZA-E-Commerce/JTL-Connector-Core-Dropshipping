<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Checksum\ChecksumInterface;

/**
 * Ack
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Internal
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Ack extends Identities
{
    /** @var Checksum[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Checksum>')]
    #[Serializer\SerializedName('checksums')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $checksums = [];

    /**
     * @param Checksum $checksum
     *
     * @return $this
     */
    public function addChecksum(Checksum $checksum): self
    {
        $this->checksums[] = $checksum;

        return $this;
    }

    /**
     * @return Checksum[]
     */
    public function getChecksums(): array
    {
        return $this->checksums;
    }

    /**
     * @return $this
     */
    public function clearChecksums(): self
    {
        $this->checksums = [];

        return $this;
    }
}
