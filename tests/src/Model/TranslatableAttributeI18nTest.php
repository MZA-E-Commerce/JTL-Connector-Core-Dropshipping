<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Model;

use JsonException;
use Jtl\Connector\Core\Exception\TranslatableAttributeException;
use Jtl\Connector\Core\Model\TranslatableAttribute;
use Jtl\Connector\Core\Model\TranslatableAttributeI18n;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class TranslatableAttributeI18nTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     *
     * @param string $type
     * @param mixed  $originalValue
     * @param mixed  $expectedValue
     * @param bool   $strictMode
     *
     * @return void
     * @throws TranslatableAttributeException
     * @throws JsonException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetValue(
        string $type,
        mixed  $originalValue,
        mixed  $expectedValue,
        bool   $strictMode = false
    ): void {
        if (
            \in_array($type, ['bool', 'boolean'], true)
            && $strictMode === true
            && !\is_bool($originalValue)
            && !\in_array($originalValue, ['0', '1'], true)
        ) {
            $this->expectException(TranslatableAttributeException::class);
            $this->expectExceptionCode(TranslatableAttributeException::VALUE_TYPE_INVALID);
        }

        TranslatableAttributeI18n::setStrictMode($strictMode);

        $i18n = (new TranslatableAttributeI18n())->setValue($originalValue);

        $actualValue = $i18n->getValue($type);

        $this->assertSame(
            $expectedValue,
            $actualValue,
            \sprintf('Casted value (%s) has the wrong type', \json_encode($actualValue))
        );
    }

    /**
     * @return array<int, array<int, string|int|float|bool|array<int|string, string>|null>>
     */
    public function getValueProvider(): array
    {
        return [
            ['int', '2', 2],
            ['int', '2.1', 2],
            ['float', '2', 2.],
            ['float', '0', 0.],
            ['float', '0.32', 0.32],
            ['bool', 'true', true],
            ['bool', 'false', false],
            ['bool', '0', false],
            ['bool', '1', true],
            ['bool', '', false],
            ['bool', 'wat', true],
            ['bool', '0', false],
            ['bool', '1', true],
            ['bool', true, true, true],
            ['bool', false, false, true],
            ['bool', '0', false, true],
            ['bool', '1', true, true],
            ['bool', 'foo', true, true],
            ['string', 'foo', 'foo'],
            ['string', '', ''],
            ['foo', 'bar', 'bar'],
            ['json', '["a", "b"]', ['a', 'b',]],
            ['json', '{"key1":"value1", "key2":"value2"}', ['key1' => 'value1', 'key2' => 'value2']],
            ['json', '...asdas', null, false]
        ];
    }

    /**
     * @return void
     * @throws JsonException
     * @throws TranslatableAttributeException
     */
    public function testGetValueAndJsonDecodingFailedInStrictMode(): void
    {
        $this->expectException(TranslatableAttributeException::class);
        $this->expectExceptionCode(TranslatableAttributeException::DECODING_VALUE_FAILED);

        TranslatableAttributeI18n::setStrictMode(true);

        $i18n = (new TranslatableAttributeI18n())
            ->setValue('asdasdas');

        $i18n->getValue(TranslatableAttribute::TYPE_JSON);
    }

    /**
     * @dataProvider setValueProvider
     *
     * @param mixed                 $value
     * @param float|int|string|bool $expectedValue
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws TranslatableAttributeException
     * @throws AssertionFailedError
     */
    public function testSetValue(mixed $value, float|int|string|bool $expectedValue): void
    {
        $translation = (new TranslatableAttributeI18n())->setValue($value);

        $translationValue = $translation->getValue();
        if (
            (
                \is_string($translationValue)
                || \is_int($translationValue)
                || \is_float($translationValue)
                || \is_bool($translationValue)
            ) === false
        ) {
            $this->fail('$translationValue must be bool|string|int|float.');
        }

        $this->assertSame(
            $translation->getValue(),
            $expectedValue,
            \sprintf(
                'Value (%s) is not equal to expected value (%s)',
                $translationValue,
                $expectedValue
            )
        );
    }

    /**
     * @return array<int, array<int, bool|string|float|int|array<int|string, string>>>
     */
    public function setValueProvider(): array
    {
        return [
            [true, '1'],
            [false, '0'],
            [0.1, '0.1'],
            [1.342342342, '1.342342342'],
            [0.0, '0'],
            [0, '0'],
            [1, '1'],
            ['foo', 'foo'],
            [['a', 'c'], '["a","c"]'],
            [['foo' => 'bar', 'baz'], '{"foo":"bar","0":"baz"}'],
        ];
    }

    /**
     * @dataProvider setValueInvalidTypeProvider
     *
     * @param mixed $value
     *
     * @return void
     * @throws JsonException
     * @throws TranslatableAttributeException
     */
    public function testSetValueWrongType(mixed $value): void
    {
        $this->expectException(TranslatableAttributeException::class);
        $this->expectExceptionCode(TranslatableAttributeException::VALUE_TYPE_INVALID);
        (new TranslatableAttributeI18n())->setValue($value);
    }

    /**
     * @return array{0: array{null}, 1: array{0: false|resource}}
     */
    public function setValueInvalidTypeProvider(): array
    {
        /** @noinspection FopenBinaryUnsafeUsageInspection */
        return [
            [null],
            [\fopen('php://temp', 'r')],
        ];
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        TranslatableAttributeI18n::setStrictMode(false);
    }
}
