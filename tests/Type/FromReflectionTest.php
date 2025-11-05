<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;

#[CoversFunction('Typhoon\Type\fromReflection')]
final class FromReflectionTest extends TestCase
{
    #[DataProvider('provideTypesCases')]
    public function testTypes(\Closure $fn, Type $expectedType): void
    {
        $returnType = (new \ReflectionFunction($fn))->getReturnType();

        $type = fromReflection($returnType);

        self::assertEquals($expectedType, $type);
    }

    /**
     * @return \Generator<array-key, array{\Closure, Type}>
     */
    public static function provideTypesCases(): iterable
    {
        yield [static fn() => 1, UntypedT::T];
        yield [static function (): void {}, voidT];
        yield [static fn(): never => throw new \LogicException(), neverT];
        yield [static fn(): false => false, falseT];
        yield [static fn(): true => true, trueT];
        yield [static fn(): bool => true, boolT];
        yield [static fn(): int => 1, intT];
        yield [static fn(): float => 0.5, floatT];
        yield [static fn(): string => 's', stringT];
        yield [static fn(): array => [], arrayT];
        yield [static fn(): object => new \stdClass(), objectT];
        yield [static fn(): self => new self('a'), selfT];
        yield [static fn(): parent => new self('a'), parentT];
        /** @phpstan-ignore return.type */
        yield [static fn(): static => new self('a'), staticT];
        yield [static fn(): iterable => [], iterableT];
        yield [static fn(): callable => trim(...), callableT];
        yield [static fn(): mixed => null, mixedT];
        yield 'class' => [
            static fn(): \stdClass => new \stdClass(),
            namedObjectT(\stdClass::class),
        ];
        yield 'interface' => [
            static fn(): \Countable => new \ArrayObject(),
            namedObjectT(\Countable::class),
        ];
        yield 'enum' => [
            static fn(): Variance => Variance::Invariant,
            namedObjectT(Variance::class),
        ];
        yield 'abstract class' => [
            static fn(): TestCase => new self('a'),
            namedObjectT(TestCase::class),
        ];
        yield 'nullable' => [static fn(): ?string => null, nullOrT(stringT)];
        yield 'union' => [
            static fn(): string|int => random_int(0, 1) ? 'a' : 1,
            unionT(stringT, intT),
        ];
        yield 'intersection' => [
            static fn(): \Countable&\Traversable => new \ArrayObject(),
            intersectionT(
                namedObjectT(\Countable::class),
                namedObjectT(\Traversable::class),
            ),
        ];
    }

    public function testItThrowsIfNameIsTrait(): void
    {
        $this->expectExceptionObject(new \LogicException('Name `Typhoon\Type\MyTrait` is not a class'));

        fromReflection(
            new class extends \ReflectionNamedType {
                public function getName(): string
                {
                    return MyTrait::class;
                }
            },
        );
    }

    public function testItThrowsIfUnknownReflection(): void
    {
        $this->expectExceptionObject(new \LogicException('`Typhoon\Type\MyReflectionType` is not supported'));

        fromReflection(new MyReflectionType());
    }
}

final class MyReflectionType extends \ReflectionType {}

/** @phpstan-ignore trait.unused */
trait MyTrait {}
