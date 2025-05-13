<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Linker;

use Jtl\Connector\Core\Checksum\ChecksumInterface;
use Jtl\Connector\Core\Checksum\ChecksumLoaderInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ChecksumLinker implements LoggerAwareInterface
{
    protected LoggerInterface          $logger;
    protected ?ChecksumLoaderInterface $loader;

    /**
     * ChecksumLinker constructor.
     *
     * @param ChecksumLoaderInterface|null $loader
     */
    public function __construct(?ChecksumLoaderInterface $loader = null)
    {
        $this->logger = new NullLogger();
        $this->loader = $loader;
    }

    /**
     * @param AbstractModel $model
     * @param int           $type
     *
     * @return ChecksumInterface|null
     */
    public static function find(AbstractModel $model, int $type): ?ChecksumInterface
    {
        if (\method_exists($model, 'getChecksums')) {
            foreach ($model->getChecksums() as $checksum) {
                if ($checksum instanceof ChecksumInterface && $checksum->getType() == $type) {
                    return $checksum;
                }
            }
        }

        return null;
    }

    /**
     * @param AbstractModel $model
     * @param string        $endpoint
     * @param int           $type
     *
     * @return ChecksumInterface|null
     */
    public static function findByEndpoint(AbstractModel $model, string $endpoint, int $type): ?ChecksumInterface
    {
        if (\method_exists($model, 'getChecksums')) {
            foreach ($model->getChecksums() as $checksum) {
                if (
                    $checksum instanceof ChecksumInterface
                    && $checksum->getType() == $type
                    && $checksum->getForeignKey()->getEndpoint() === $endpoint
                ) {
                    return $checksum;
                }
            }
        }

        return null;
    }

    /**
     * @param AbstractModel $model
     * @param int           $host
     * @param int           $type
     *
     * @return ChecksumInterface|null
     */
    public static function findByHost(AbstractModel $model, int $host, int $type): ?ChecksumInterface
    {
        if (\method_exists($model, 'getChecksums')) {
            foreach ($model->getChecksums() as $checksum) {
                if (
                    $checksum instanceof ChecksumInterface
                    && $checksum->getType() == $type
                    && $checksum->getForeignKey()->getHost() == $host
                ) {
                    return $checksum;
                }
            }
        }

        return null;
    }

    /**
     * @param AbstractModel $model
     * @param int|null      $type
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function link(AbstractModel $model, ?int $type = null): void
    {
        if (!\is_null($this->loader) && \method_exists($model, 'getChecksums')) {
            $checksums = $model->getChecksums();
            foreach ($checksums as &$checksum) {
                if ($checksum instanceof ChecksumInterface && ($type === null || $checksum->getType() === $type)) {
                    $this->logger->debug('Checksum linking type ({type})...', ['type' => $type]);

                    if (
                        \method_exists($model, 'getId')
                        && $model->getId()->getEndpoint() !== null
                        && $model->getId()->getEndpoint() !== ''
                    ) {
                        $checksum->setEndpoint(
                            $this->loader->read($model->getId()->getEndpoint(), $checksum->getType())
                        );

                        if ($checksum->getEndpoint() !== null && $checksum->getEndpoint() !== '') {
                            if (($checksum->getEndpoint() !== $checksum->getHost())) {
                                $this->logger->debug(
                                    'Changed checksum for endpoint ({endpoint}) type ({type})',
                                    [
                                        'endpoint' => $model->getId()->getEndpoint(),
                                        'type'     => $type,
                                    ]
                                );
                                $checksum->setHasChanged(true);
                                $this->loader->delete($model->getId()->getEndpoint(), $checksum->getType());
                                $this->loader->write(
                                    $model->getId()->getEndpoint(),
                                    $checksum->getType(),
                                    $checksum->getHost()
                                );
                            }
                        } else {
                            $this->logger->debug(
                                'Write new checksum for endpoint ({endpoint}) type ({type})',
                                [
                                    'endpoint' => $model->getId()->getEndpoint(),
                                    'type'     => $type,
                                ]
                            );
                            $checksum->setHasChanged(true);
                            $this->loader->write(
                                $model->getId()->getEndpoint(),
                                $checksum->getType(),
                                $checksum->getHost()
                            );
                        }
                    } else {
                        $this->logger->debug('New checksum with empty endpoint type ({type})', ['type' => $type]);
                        $checksum->setHasChanged(true);
                    }
                }
            }
        }
    }

    /**
     * @param ChecksumInterface $checksum
     *
     * @return bool
     */
    public function save(ChecksumInterface $checksum): bool
    {
        if (\is_null($this->loader)) {
            return true;
        }

        if ($checksum->getForeignKey()->getEndpoint() !== '' && $checksum->getForeignKey()->getHost()) {
            $this->loader->delete($checksum->getForeignKey()->getEndpoint(), $checksum->getType());
            $this->loader->write($checksum->getForeignKey()->getEndpoint(), $checksum->getType(), $checksum->getHost());

            return true;
        }

        return false;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
