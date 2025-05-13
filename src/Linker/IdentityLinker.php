<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Linker;

use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\LinkerException;
use Jtl\Connector\Core\Mapper\PrimaryKeyMapperInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Identity;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionException;

class IdentityLinker implements LoggerAwareInterface
{
    public const
        CACHE_TYPE_HOST     = 'h',
        CACHE_TYPE_ENDPOINT = 'e';

    protected LoggerInterface $logger;

    /**
     * Session Database Mapper
     */
    protected PrimaryKeyMapperInterface $mapper;
    protected bool                      $useCache = true;

    /** @var array<string, int|string>|array{} */
    protected array $cache = [];

    /**
     * IdentityLinker constructor.
     *
     * @param PrimaryKeyMapperInterface $mapper
     */
    public function __construct(PrimaryKeyMapperInterface $mapper)
    {
        $this->logger = new NullLogger();
        $this->mapper = $mapper;
    }

    /**
     * @param bool $useCache
     *
     * @return $this
     */
    public function setUseCache(bool $useCache): self
    {
        $this->useCache = $useCache;

        return $this;
    }

    /**
     * @param AbstractModel $model
     * @param bool          $isDeleted
     *
     * @return void
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     * @throws ReflectionException
     */
    public function linkModel(AbstractModel $model, bool $isDeleted = false): void
    {
        $reflect   = new \ReflectionClass($model);
        $modelName = $reflect->getShortName();

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PROTECTED) as $propertyInfo) {
            $propertyName = $propertyInfo->getName();
            $getter       = \sprintf('get%s', \ucfirst($propertyName));

            if (\is_callable([$model, $getter])) {
                $value = $model->{$getter}();
                if (!\is_array($value)) {
                    $value = [$value];
                }

                foreach ($value as $entity) {
                    if ($entity instanceof Identity && Model::isIdentityProperty($modelName, $propertyName)) {
                        $this->linkIdentity($entity, $modelName, $propertyName, $isDeleted);
                    } elseif ($entity instanceof AbstractModel) {
                        $this->linkModel($entity, $isDeleted);
                    }
                }
            }
        }
    }

    /**
     * @param Identity $identity
     * @param string   $modelName
     * @param string   $propertyName
     * @param bool     $isDeleted
     *
     * @return void
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     */
    public function linkIdentity(
        Identity $identity,
        string   $modelName,
        string   $propertyName,
        bool     $isDeleted = false
    ): void {
        $endpoint     = $identity->getEndpoint();
        $host         = $identity->getHost();
        $identityType = Model::getPropertyIdentityType($modelName, $propertyName);

        if ($endpoint !== '' && $host > 0) {
            if ($isDeleted) {
                $this->delete($modelName, $endpoint, $host);
            } elseif (
                !$this->propertyHostIdExists($modelName, $propertyName, $host)
                || $this->loadCache($host, $identityType, self::CACHE_TYPE_HOST) !== $endpoint
            ) {
                $this->save($endpoint, $host, $modelName, $propertyName);
            }
        } elseif ($host > 0) {
            if ($isDeleted) {
                $this->delete($modelName, null, $host);
            } elseif ($this->propertyHostIdExists($modelName, $propertyName, $host)) {
                $identity->setEndpoint($this->getEndpointId($modelName, $propertyName, $host));
            }
        } elseif ($endpoint !== '') {
            if ($isDeleted) {
                $this->delete($modelName, $endpoint);
            } elseif ($this->propertyEndpointIdExists($modelName, $propertyName, $endpoint)) {
                $identity->setHost($this->getHostId($modelName, $propertyName, $endpoint));
            }
        }
    }

    /**
     * @param string      $modelName
     * @param string|null $endpointId
     * @param int|null    $hostId
     *
     * @return bool
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function delete(string $modelName, ?string $endpointId = null, ?int $hostId = null): bool
    {
        $identityType = Model::getIdentityType($modelName);

        $result = $this->mapper->delete($identityType, $endpointId, $hostId);
        if ($result) {
            $this->deleteCache($identityType, self::CACHE_TYPE_ENDPOINT, $endpointId, null);
            $this->deleteCache($identityType, self::CACHE_TYPE_HOST, null, $hostId);

            return true;
        }

        return false;
    }

    /**
     * @param int         $identityType
     * @param string      $cacheType
     * @param string|null $endpointId
     * @param int|null    $hostId
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function deleteCache(
        int    $identityType,
        string $cacheType,
        ?string $endpointId = null,
        ?int    $hostId = null
    ): void {
        $context = [
            'endpoint'     => $endpointId,
            'host'         => $hostId,
            'identityType' => $identityType,
            'cacheType'    => $cacheType,
        ];

        $this->logger->debug(
            'DeleteCache 
            (endpointId: {endpoint}, hostId: {host}, identityType: {identityType}, cacheType: {cacheType})',
            $context
        );

        if ($this->useCache) {
            switch ($cacheType) {
                case self::CACHE_TYPE_ENDPOINT:
                    unset($this->cache[$this->buildKey($endpointId, $identityType, $cacheType)]);
                    break;
                case self::CACHE_TYPE_HOST:
                    unset($this->cache[$this->buildKey($hostId, $identityType, $cacheType)]);
                    break;
            }
        }
    }

    /**
     * @param mixed  $id
     * @param int    $identityType
     * @param string $cacheType
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function buildKey(mixed $id, int $identityType, string $cacheType): string
    {
        if ($id !== null && !\is_scalar($id)) {
            throw new \InvalidArgumentException('$id must be scalar or null.');
        }

        return \sprintf('%s_%s_%s', $cacheType, $id, $identityType);
    }

    /**
     * @param string $modelName
     * @param string $property
     * @param int    $hostId
     *
     * @return bool
     * @throws DefinitionException
     * @throws LinkerException
     * @throws \InvalidArgumentException
     */
    public function propertyHostIdExists(string $modelName, string $property, int $hostId): bool
    {
        if (!$this->isValidHostId($hostId)) {
            throw new LinkerException(
                \sprintf(
                    'HostId (%s) => (modelName: %s, property: %s) is invalid.',
                    $hostId,
                    $modelName,
                    $property
                )
            );
        }

        $identityType = Model::getPropertyIdentityType($modelName, $property);
        $modelName    = Model::getModelByType($identityType);

        return $this->hostIdExists($modelName, $hostId);
    }

    /**
     * @param mixed $hostId
     *
     * @return bool
     */
    public function isValidHostId(mixed $hostId): bool
    {
        return \is_int($hostId) && $hostId > 0;
    }

    /**
     * @param string $modelName
     * @param int    $hostId
     *
     * @return bool
     * @throws DefinitionException
     * @throws LinkerException
     * @throws \InvalidArgumentException
     */
    public function hostIdExists(string $modelName, int $hostId): bool
    {
        if (!$this->isValidHostId($hostId)) {
            throw new LinkerException(
                \sprintf(
                    'HostId (%s) => (modelName: %s) is invalid.',
                    $hostId,
                    $modelName
                )
            );
        }

        $identityType = Model::getIdentityType($modelName);
        if (!$this->checkCache($hostId, $identityType, self::CACHE_TYPE_HOST)) {
            $endpointId = $this->mapper->getEndpointId($identityType, $hostId);
            if (!$this->isValidEndpointId($endpointId)) {
                return false;
            }
            /** @var string $endpointId */
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_HOST);
        }

        return true;
    }

    /**
     * @param mixed  $id
     * @param int    $identityType
     * @param string $cacheType
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function checkCache(mixed $id, int $identityType, string $cacheType): bool
    {
        $result = $this->useCache && \array_key_exists($this->buildKey($id, $identityType, $cacheType), $this->cache);

        $context = [
            'id'           => $id,
            'identityType' => $identityType,
            'cacheType'    => $cacheType,
            'result'       => $result ? 'true' : 'false',
        ];

        $this->logger->debug(
            'CheckCache (id: {id}, identityType: {identityType}, cacheType: {cacheType}) with result {result}',
            $context
        );

        return $result;
    }

    /**
     * @param string $modelName
     * @param string $property
     * @param int    $hostId
     *
     * @return string
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function getEndpointId(string $modelName, string $property, int $hostId): string
    {
        $identityType = Model::getPropertyIdentityType($modelName, $property);
        if (
            ($endpointId = $this->loadCache($hostId, $identityType, self::CACHE_TYPE_HOST)) !== null
            && \is_string($endpointId)
        ) {
            return $endpointId;
        }

        $endpointId = (string)$this->mapper->getEndpointId($identityType, $hostId);

        if (\trim($endpointId) !== '') {
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_HOST);
        }

        return $endpointId;
    }

    /**
     * @param mixed  $id
     * @param int    $identityType
     * @param string $cacheType
     *
     * @return int|string|null
     * @throws \InvalidArgumentException
     */
    protected function loadCache(mixed $id, int $identityType, string $cacheType): int|string|null
    {
        $result = $this->checkCache($id, $identityType, $cacheType)
            ? $this->cache[$this->buildKey($id, $identityType, $cacheType)]
            : null;

        $context = [
            'id'           => $id,
            'identityType' => $identityType,
            'cacheType'    => $cacheType,
            'result'       => $result ? 'true' : 'false',
        ];

        $this->logger->debug(
            'LoadCache (id: {id}, identityType: {identityType}, cacheType: {cacheType}) with result {result}',
            $context
        );

        return $result;
    }

    /**
     * @param string $endpointId
     * @param int    $hostId
     * @param int    $identityType
     * @param string $cacheType
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function saveCache(string $endpointId, int $hostId, int $identityType, string $cacheType): void
    {
        $context = [
            'endpoint'     => $endpointId,
            'host'         => $hostId,
            'identityType' => $identityType,
            'cacheType'    => $cacheType,
        ];

        $this->logger->debug(
            'SaveCache (endpointId: {endpoint}, hostId: {host}, identityType: {identityType}, cacheType: {cacheType})',
            $context
        );

        if ($this->useCache) {
            switch ($cacheType) {
                case self::CACHE_TYPE_ENDPOINT:
                    $this->cache[$this->buildKey($endpointId, $identityType, $cacheType)] = $hostId;
                    break;
                case self::CACHE_TYPE_HOST:
                    $this->cache[$this->buildKey($hostId, $identityType, $cacheType)] = $endpointId;
                    break;
            }
        }
    }

    /**
     * @param mixed $endpointId
     *
     * @return bool
     */
    public function isValidEndpointId(mixed $endpointId): bool
    {
        return \is_string($endpointId) && \trim($endpointId) !== '';
    }

    /**
     * @param string      $endpointId
     * @param int         $hostId
     * @param string      $modelName
     * @param string|null $property
     *
     * @return bool
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function save(string $endpointId, int $hostId, string $modelName, ?string $property = null): bool
    {
        $identityType = \is_null($property)
            ? Model::getIdentityType($modelName)
            : Model::getPropertyIdentityType($modelName, $property);
        $relatedModel = Model::getModelByType($identityType);
        $this->delete($relatedModel, $endpointId, $hostId);

        $result = $this->mapper->save($identityType, $endpointId, $hostId);
        if ($result) {
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_ENDPOINT);
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_HOST);

            return true;
        }

        return false;
    }

    /**
     * @param string $modelName
     * @param string $property
     * @param string $endpointId
     *
     * @return bool
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     */
    public function propertyEndpointIdExists(string $modelName, string $property, string $endpointId): bool
    {
        if (!$this->isValidEndpointId($endpointId)) {
            throw new LinkerException(
                \sprintf(
                    'EndpointId (%s) => (modelName: %s, property: %s) is invalid.',
                    $endpointId,
                    $modelName,
                    $property
                )
            );
        }

        $identityType = Model::getPropertyIdentityType($modelName, $property);
        $modelName    = Model::getModelByType($identityType);

        return $this->endpointIdExists($modelName, $endpointId);
    }

    /**
     * @param string $modelName
     * @param string $endpointId
     *
     * @return bool
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     * @throws LinkerException
     */
    public function endpointIdExists(string $modelName, string $endpointId): bool
    {
        if (!$this->isValidEndpointId($endpointId)) {
            throw new LinkerException(
                \sprintf(
                    'EndpointId (%s) => (modelName: %s) is invalid.',
                    $endpointId,
                    $modelName
                )
            );
        }

        $identityType = Model::getIdentityType($modelName);
        if (!$this->checkCache($endpointId, $identityType, self::CACHE_TYPE_ENDPOINT)) {
            $hostId = $this->mapper->getHostId($identityType, $endpointId);
            if (!$this->isValidHostId($hostId)) {
                return false;
            }
            /** @var int $hostId */
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_ENDPOINT);
        }

        return true;
    }

    /**
     * @param string $modelName
     * @param string $property
     * @param string $endpointId
     *
     * @return int
     * @throws DefinitionException
     * @throws \InvalidArgumentException
     */
    public function getHostId(string $modelName, string $property, string $endpointId): int
    {
        $identityType = Model::getPropertyIdentityType($modelName, $property);

        if (
            ($hostId = $this->loadCache($endpointId, $identityType, self::CACHE_TYPE_ENDPOINT)) !== null
            && \is_int($hostId) && $this->isValidHostId($hostId)
        ) {
            return $hostId;
        }

        $hostId = (int)$this->mapper->getHostId($identityType, $endpointId);

        if ($hostId > 0) {
            $this->saveCache($endpointId, $hostId, $identityType, self::CACHE_TYPE_ENDPOINT);
        }

        return $hostId;
    }

    /**
     * Clears the entire link table
     *
     * @param int|null $identityType
     *
     * @return bool
     */
    public function clear(?int $identityType = null): bool
    {
        return $this->mapper->clear($identityType);
    }

    /**
     * @return array<string, int|string>|array{}
     */
    public function getCache(): array
    {
        return $this->cache;
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
