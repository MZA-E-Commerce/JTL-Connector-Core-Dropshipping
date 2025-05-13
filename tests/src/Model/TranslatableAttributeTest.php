<?php

declare(strict_types=1);

namespace src\Model;

use JsonException;
use Jtl\Connector\Core\Exception\TranslatableAttributeException;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Generator\AbstractModelFactory;
use Jtl\Connector\Core\Model\Generator\TranslatableAttributeI18nFactory;
use Jtl\Connector\Core\Model\TranslatableAttribute;
use Jtl\Connector\Core\Model\TranslatableAttributeI18n;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\ClassIsReadonlyException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class TranslatableAttributeTest extends TestCase
{
    /**
     * @dataProvider findTranslationProvider
     *
     * @param string                           $languageIso
     * @param TranslatableAttributeI18n|null   $expectedTranslation
     * @param array<TranslatableAttributeI18n> $translations
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testFindTranslation(
        string                     $languageIso,
        ?TranslatableAttributeI18n $expectedTranslation,
        array                      $translations = []
    ): void {
        $attribute = (new TranslatableAttribute())
            ->setI18ns(...$translations);

        $actualTranslation = $attribute->findTranslation($languageIso);

        $this->assertEquals($expectedTranslation, $actualTranslation);
    }

    /**
     * @return array<int, array<int, string|TranslatableAttributeI18n|array<int, TranslatableAttributeI18n>|null>>
     * @throws \Exception
     */
    public function findTranslationProvider(): array
    {
        /** @var TranslatableAttributeI18nFactory $translationsFactory */
        $translationsFactory = AbstractModelFactory::createFactory('TranslatableAttributeI18n');

        /** @var TranslatableAttributeI18n[] $translations */
        $translations      = $translationsFactory->make(\random_int(2, 10));
        $translationsCount = \count($translations);
        $this->assertGreaterThan(1, $translationsCount);
        $randomIntMax = $translationsCount - 1;
        if ($randomIntMax < 1) {
            $this->fail('$randomIntMax must be greater than 0.');
        }

        return [
            [
                'es',
                $translations[\random_int(0, $randomIntMax)]->setLanguageIso('es'),
                $translations,
            ],
            [
                'notFound',
                $translations[0],
                $translations,
            ],
            [
                'foo',
                null,
            ],
        ];
    }

    /**
     * @dataProvider findValueProvider
     *
     * @param string                         $type
     * @param TranslatableAttributeI18n|null $translation
     * @param mixed                          $expectedValue
     *
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws IncompatibleReturnValueException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws TranslatableAttributeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @depends      testSetType
     */
    public function testFindValue(string $type, ?TranslatableAttributeI18n $translation, mixed $expectedValue): void
    {
        $attribute = $this->createPartialMock(TranslatableAttribute::class, ['findTranslation']);

        $languageIso = 'foo';

        $attribute
            ->expects($this->once())
            ->method('findTranslation')
            ->with($languageIso)
            ->willReturn($translation);

        $attribute->setType($type);

        $actualValue = $attribute->findValue($languageIso);

        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * @return array<int, array<int, string|AbstractModel|int|null>>
     * @throws \Exception
     */
    public function findValueProvider(): array
    {
        /** @var TranslatableAttributeI18nFactory $translationsFactory */
        $translationsFactory = AbstractModelFactory::createFactory('TranslatableAttributeI18n');

        return [
            [
                'int',
                $translationsFactory->makeOne(['value' => '123']),
                123,
            ],
            [
                'string',
                null,
                null,
            ],
        ];
    }

    /**
     * @dataProvider getNameProvider
     *
     * @param array<TranslatableAttributeI18n> $translations
     * @param string                           $expectedName
     * @param string                           $languageIso
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetName(array $translations, string $expectedName, string $languageIso): void
    {
        $attribute = (new TranslatableAttribute())
            ->setI18ns(...$translations);

        $actualName = $attribute->getName($languageIso);

        $this->assertEquals($expectedName, $actualName);
    }

    /**
     * @return array<int, array{0: TranslatableAttributeI18n[], 1: string, 2: string}>
     * @throws \Exception
     */
    public function getNameProvider(): array
    {
        /** @var TranslatableAttributeI18nFactory $translationsFactory */
        $translationsFactory = AbstractModelFactory::createFactory('TranslatableAttributeI18n');
        $rounds              = \random_int(1, 5);
        $translations        = [];

        $data = [];
        for ($i = 0; $i < $rounds; $i++) {
            /** @var TranslatableAttributeI18n[] $translations */
            $translations = $translationsFactory->make(\random_int(1, 5));
            $randomIntMax = \count($translations) - 1;

            if ($randomIntMax < 1) {
                $selected = 0;
            } else {
                $selected = \random_int(0, $randomIntMax);
            }

            $data[] = [
                $translations,
                $translations[$selected]->getName(),
                $translations[$selected]->getLanguageIso(),
            ];
        }

        $data[] = [
            $translations,
            $translations[0]->getName(),
            '',
        ];

        return $data;
    }

    /**
     * @dataProvider getValuesProvider
     *
     * @param TranslatableAttributeI18n[]                      $translations
     * @param array<string, bool|float|int|string|object|null> $expectedValues
     * @param string|null                                      $castToType
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws TranslatableAttributeException
     */
    public function testGetValues(array $translations, array $expectedValues, ?string $castToType = null): void
    {
        $attribute = (new TranslatableAttribute())
            ->setI18ns(...$translations);

        $actualValues = $attribute->getValues($castToType);

        $this->assertEquals($expectedValues, $actualValues);
    }

    /**
     * @return array<int, array{0: TranslatableAttributeI18n[], 1: array<string, string>}>
     * @throws \Exception
     */
    public function getValuesProvider(): array
    {
        /** @var TranslatableAttributeI18nFactory $translationsFactory */
        $translationsFactory = AbstractModelFactory::createFactory('TranslatableAttributeI18n');
        $rounds              = \random_int(1, 5);

        $data = [];
        for ($i = 0; $i < $rounds; $i++) {
            /** @var TranslatableAttributeI18n[] $translations */
            $translations   = $translationsFactory->make(\random_int(1, 5));
            $expectedValues = [];
            foreach ($translations as $translation) {
                $expectedValues[$translation->getLanguageIso()] = $translation->getValueAsString();
            }

            $data[] = [
                $translations,
                $expectedValues,
            ];
        }

        return $data;
    }

    /**
     * @param string $actualType
     * @param string $expectedType
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @dataProvider setTypeProvider
     */
    public function testSetType(string $actualType, string $expectedType): void
    {
        $attribute = new TranslatableAttribute();
        $attribute->setType($actualType);

        $this->assertEquals($expectedType, $attribute->getType());
    }

    /**
     * @return array<int, array<int, bool|string>>
     */
    public function setTypeProvider(): array
    {
        $data = [];
        foreach (TranslatableAttribute::getTypes() as $type) {
            $data[] = [
                $type,
                $type,
            ];
        }

        return \array_merge($data, [
            //invalid types, should fall back to string
            [
                'something invalid',
                TranslatableAttribute::TYPE_STRING,
            ],
            [
                '',
                TranslatableAttribute::TYPE_STRING,
            ]
        ]);
    }
}
