<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Serializer\Subscriber;

use Doctrine\Common\Annotations\AnnotationException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JsonException;
use Jtl\Connector\Core\Model\AbstractI18n;
use Jtl\Connector\Core\Model\CategoryI18n;
use Jtl\Connector\Core\Model\CrossSellingGroupI18n;
use Jtl\Connector\Core\Model\ImageI18n;
use Jtl\Connector\Core\Model\ProductI18n;
use Jtl\Connector\Core\Model\TranslatableAttributeI18n;
use Jtl\Connector\Core\Serializer\SerializerBuilder;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class LanguageIsoSubscriberTest
 *
 * @package Jtl\Connector\Core\Test\Serializer\Subscriber
 */
class LanguageIsoSubscriberTest extends TestCase
{
    /**
     * @return array<int, array{0: class-string}>
     */
    public function i18NDataProvider(): array
    {
        return [
            [ProductI18n::class],
            [CategoryI18n::class],
            [ImageI18n::class],
            [CrossSellingGroupI18n::class],
            [TranslatableAttributeI18n::class],
        ];
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param class-string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPostSerializeWithInvalidValue(string $model): void
    {
        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $i18nModel->setLanguageIso('___');

        $serializedData = $this->serializeModel($i18nModel);

        /** @var object $jsonObj */
        $jsonObj = \json_decode($serializedData, false, 512, \JSON_THROW_ON_ERROR);
        if (!\property_exists($jsonObj, 'languageISO')) {
            $this->fail('property "languageISO" does not exist.');
        }
        if (!\property_exists($jsonObj, 'languageIso')) {
            $this->fail('property "languageIso" does not exist.');
        }
        $this->assertEquals($jsonObj->languageISO, $jsonObj->languageIso);
    }

    /**
     * @param AbstractI18n $i18nModel
     *
     * @return string
     * @throws AnnotationException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    protected function serializeModel(AbstractI18n $i18nModel): string
    {
        return SerializerBuilder::create()->build()->serialize($i18nModel, 'json');
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param class-string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPostSerializeWithEmptyValue(string $model): void
    {
        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $i18nModel->setLanguageIso('');

        $serializedData = $this->serializeModel($i18nModel);

        /** @var object $jsonObj */
        $jsonObj = \json_decode($serializedData, false, 512, \JSON_THROW_ON_ERROR);
        if (!\property_exists($jsonObj, 'languageIso')) {
            $this->fail('property "languageIso" does not exist.');
        }
        $this->assertSame($jsonObj->languageIso, '');
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPostSerializeWithValidValue(string $model): void
    {
        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $i18nModel->setLanguageIso('de');

        $serializedData = $this->serializeModel($i18nModel);

        /** @var object $jsonObj */
        $jsonObj = \json_decode($serializedData, false, 512, \JSON_THROW_ON_ERROR);

        if (!\property_exists($jsonObj, 'languageISO')) {
            $this->fail('property "languageISO" does not exist.');
        }
        $this->assertEquals('ger', $jsonObj->languageISO);
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param class-string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPostSerializeWithNoValue(string $model): void
    {
        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $serializedData = $this->serializeModel($i18nModel);

        /** @var object $jsonObj */
        $jsonObj = \json_decode($serializedData, false, 512, \JSON_THROW_ON_ERROR);

        if (!\property_exists($jsonObj, 'languageISO')) {
            $this->fail('property "languageISO" does not exist.');
        }
        $this->assertEquals('', $jsonObj->languageISO);
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param class-string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPreDeserializeWithValidValue(string $model): void
    {
        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $i18nModel->setLanguageIso('de');

        $deserializeData = $this->serializeAndDeserializeModel($i18nModel);

        if (!\property_exists($deserializeData, 'languageIso')) {
            $this->fail('property "languageISO" does not exist.');
        }
        $this->assertSame($deserializeData->getLanguageIso(), 'de');
    }

    /**
     * @param AbstractI18n $i18nModel
     *
     * @return ProductI18n
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     */
    protected function serializeAndDeserializeModel(AbstractI18n $i18nModel): ProductI18n
    {
        $serializer  = SerializerBuilder::create()->build();
        $productI18n = $serializer->deserialize($serializer->serialize($i18nModel, 'json'), ProductI18n::class, 'json');
        $this->assertInstanceOf(ProductI18n::class, $productI18n);

        return $productI18n;
    }

    /**
     * @dataProvider i18NDataProvider
     *
     * @param class-string $model
     *
     * @return void
     * @throws AnnotationException
     * @throws AssertionFailedError
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     */
    public function testOnPreDeserializeWithInValidValue(string $model): void
    {
        $value = '_____';

        $i18nModel = new $model();
        $this->assertInstanceOf(AbstractI18n::class, $i18nModel);
        $i18nModel->setLanguageIso($value);

        $deserializeData = $this->serializeAndDeserializeModel($i18nModel);

        if (!\property_exists($deserializeData, 'languageIso')) {
            $this->fail('property "languageISO" does not exist.');
        }
        $this->assertSame($deserializeData->getLanguageIso(), $value);
    }
}
