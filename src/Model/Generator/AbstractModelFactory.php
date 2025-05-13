<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Doctrine\Common\Annotations\AnnotationException;
use Faker\Factory;
use Faker\Generator;
use JMS\Serializer\Exception\InvalidArgumentException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\Serializer;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Serializer\SerializerBuilder;

/**
 * Class ModelFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
abstract class AbstractModelFactory
{
    /** @var array<int, array<string, int>> */
    protected static array $identities = [];
    /** @var AbstractModelFactory[] */
    protected static array $factories = [];
    protected string       $defaultLocale;
    protected Generator    $faker;
    protected Serializer   $serializer;

    /**
     * AbstractModelFactory constructor.
     *
     * @param string          $defaultLocale
     * @param Generator|null  $faker
     * @param Serializer|null $serializer
     *
     * @throws AnnotationException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function __construct(
        string      $defaultLocale = 'de_DE',
        ?Generator  $faker = null,
        ?Serializer $serializer = null
    ) {
        $this->defaultLocale = $defaultLocale;

        if ($faker === null) {
            $faker = Factory::create($defaultLocale);
        }
        $this->faker = $faker;

        if ($serializer === null) {
            $serializer = SerializerBuilder::create()->build();
        }
        $this->serializer = $serializer;
    }

    /**
     * @param int $identityType
     * @param int $host
     *
     * @return array<int, int|string>|null
     */
    public static function getIdentityByHost(int $identityType, int $host): ?array
    {
        if (
            self::hasIdentityByHost($identityType, $host)
            && ($endpoint = \array_search($host, self::$identities[$identityType], true)) !== false
        ) {
            return self::getIdentity($identityType, $endpoint);
        }

        return null;
    }

    /**
     * @param int $identityType
     * @param int $host
     *
     * @return bool
     */
    public static function hasIdentityByHost(int $identityType, int $host): bool
    {
        return isset(self::$identities[$identityType]) && \in_array($host, self::$identities[$identityType], true);
    }

    /**
     * @param int    $identityType
     * @param string $endpoint
     *
     * @return array{0: string, 1: int}|null
     */
    public static function getIdentity(int $identityType, string $endpoint): ?array
    {
        if (self::hasIdentity($identityType, $endpoint)) {
            return [
                $endpoint,
                self::$identities[$identityType][$endpoint],
            ];
        }

        return null;
    }

    /**
     * @param int    $identityType
     * @param string $endpoint
     *
     * @return bool
     */
    public static function hasIdentity(int $identityType, string $endpoint): bool
    {
        return isset(self::$identities[$identityType][$endpoint]);
    }

    /**
     * @param int          $quantity
     * @param array<mixed> $specificOverrides
     * @param array<mixed> $globalOverrides
     *
     * @return array<int, array<string|int, mixed>>
     */
    public function makeArray(int $quantity, array $specificOverrides = [], array $globalOverrides = []): array
    {
        $models = [];
        for ($i = 0; $i < $quantity; $i++) {
            $models[] = $this->makeOneArray(\array_merge($globalOverrides, $specificOverrides[$i] ?? []));
        }

        return $models;
    }

    /**
     * @param array<mixed> $override
     *
     * @return array<string|int, mixed>
     */
    public function makeOneArray(array $override = []): array
    {
        return $override + $this->makeFakeArray();
    }

    /**
     * @return array<string|int, mixed>
     */
    abstract protected function makeFakeArray(): array;

    /**
     * @param array<mixed> $override
     *
     * @return AbstractModel
     * @throws \Exception
     */
    public function makeOne(array $override = []): AbstractModel
    {
        return $this->make(1, [$override])[0];
    }

    /**
     * @param int          $quantity
     * @param array<mixed> $specificOverrides
     * @param array<mixed> $globalOverrides
     *
     * @return array<int, AbstractModel>
     * @throws LogicException
     * @throws RuntimeException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     */
    public function make(int $quantity, array $specificOverrides = [], array $globalOverrides = []): array
    {
        $models = [];
        for ($i = 0; $i < $quantity; $i++) {
            /** @var AbstractModel $model */
            $model    = $this->serializer->fromArray(
                $this->makeOneArray(\array_merge($globalOverrides, $specificOverrides[$i] ?? [])),
                $this->getModelClass()
            );
            $models[] = $model;
        }

        return $models;
    }

    /**
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * @param int $identityType
     *
     * @return mixed
     * @throws \Exception
     */
    public function makeIdentity(int $identityType): mixed
    {
        return $this->serializer->fromArray($this->makeIdentityArray($identityType), Identity::class);
    }

    /**
     * @param int $identityType
     *
     * @return array{0: string, 1: int}
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function makeIdentityArray(int $identityType): array
    {
        /** @var IdentityFactory $identityFactory */
        $identityFactory = $this->getFactory('Identity');
        do {
            /** @var string $endpoint */
            /** @var int $host */
            [
                $endpoint,
                $host,
            ] = $identityFactory->makeOneArray();
            if (!isset(self::$identities[$identityType][$endpoint])) {
                break;
            }
        } while (true);

        self::setIdentity($identityType, $endpoint, $host);

        /** @var array{0: string, 1: int} $identity */
        $identity = self::getIdentity($identityType, $endpoint);

        return $identity;
    }

    /**
     * @param string $name
     *
     * @return AbstractModelFactory
     * @throws \RuntimeException
     */
    public function getFactory(string $name): AbstractModelFactory
    {
        return self::createFactory($name, $this->defaultLocale, $this->faker, $this->serializer);
    }

    /**
     * @param string          $name
     * @param string          $locale
     * @param Generator|null  $faker
     * @param Serializer|null $serializer
     *
     * @return AbstractModelFactory
     * @throws \RuntimeException
     */
    public static function createFactory(
        string      $name,
        string      $locale = 'de_DE',
        ?Generator  $faker = null,
        ?Serializer $serializer = null
    ): AbstractModelFactory {
        $className = \sprintf('%s\\%sFactory', __NAMESPACE__, \ucfirst($name));
        if (!\class_exists($className)) {
            throw new \RuntimeException(\sprintf('Class %s not found', $className));
        }

        $locale         = \str_replace('-', '_', $locale);
        $factoriesIndex = \md5(\sprintf('%s%s', $className, $locale));
        if (!isset(self::$factories[$factoriesIndex])) {
            /** @var AbstractModelFactory $factoryClass */
            $factoryClass                     = new $className($locale, $faker, $serializer);
            self::$factories[$factoriesIndex] = $factoryClass;
        }

        return self::$factories[$factoriesIndex];
    }

    /**
     * @param int    $identityType
     * @param string $endpoint
     * @param int    $host
     *
     * @return void
     */
    public static function setIdentity(int $identityType, string $endpoint, int $host): void
    {
        self::$identities[$identityType][$endpoint] = $host;
    }

    /**
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return $this->faker;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return string
     */
    protected function dateBetween(string $from, string $to = 'now'): string
    {
        return $this->faker->dateTimeBetween($from, $to)->format(\DateTimeInterface::ATOM);
    }
}
