<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractModel
{
    /** @var string[] */
    #[Serializer\Exclude]
    private array $identificationStrings = [];

    /**
     * @param string $identificationString
     *
     * @return $this
     */
    public function setIdentificationString(string $identificationString): self
    {
        if (!\in_array($identificationString, $this->identificationStrings, true)) {
            $this->identificationStrings[] = $identificationString;
        }

        return $this;
    }

    /**
     * @param string $subject
     * @param string $identificationString
     *
     * @return $this
     */
    public function setIdentificationStringBySubject(string $subject, string $identificationString): self
    {
        $this->identificationStrings[$subject] = $identificationString;

        return $this;
    }

    /**
     * @param string $identificationString
     *
     * @return $this
     */
    public function unsetIdentificationString(string $identificationString): self
    {
        $index = \array_search($identificationString, $this->identificationStrings, true);
        if ($index !== false) {
            unset($this->identificationStrings[$index]);
        }

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function unsetIdentificationStringBySubject(string $subject): self
    {
        unset($this->identificationStrings[$subject]);

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getIdentificationStrings(): array
    {
        return \array_values($this->identificationStrings);
    }

    /**
     * @param string ...$identificationStrings
     *
     * @return $this
     */
    public function setIdentificationStrings(string ...$identificationStrings): self
    {
        $this->identificationStrings = $identificationStrings;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasIdentificationStrings(): bool
    {
        return \count($this->identificationStrings) > 0;
    }
}
