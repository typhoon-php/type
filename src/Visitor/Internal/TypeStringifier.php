<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor\Internal;

use Typhoon\Type\ClassStringT;
use Typhoon\Type\DiffT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NullT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor\DefaultTypeVisitor;
use Typhoon\Type\VoidT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @extends DefaultTypeVisitor<non-empty-string>
 */
final class TypeStringifier extends DefaultTypeVisitor
{
    public function never(NeverT $type): mixed
    {
        return 'never';
    }

    public function void(VoidT $type): mixed
    {
        return 'void';
    }

    public function null(NullT $type): mixed
    {
        return 'null';
    }

    public function true(TrueT $type): mixed
    {
        return 'true';
    }

    public function false(FalseT $type): mixed
    {
        return 'false';
    }

    public function intRange(IntRangeT $type): mixed
    {
        return \sprintf('int<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function floatRange(FloatRangeT $type): mixed
    {
        return \sprintf('float<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function string(Type $type): mixed
    {
        return 'string';
    }

    public function stringValue(StringValueT $type): mixed
    {
        return $this->escapeStringLiteral($type->value);
    }

    public function classString(ClassStringT $type): mixed
    {
        return \sprintf('class-string<%s>', $type->objectType->accept($this));
    }

    public function resource(Type $type): mixed
    {
        return 'resource';
    }

    public function union(UnionT $type): mixed
    {
        return implode('|', array_map(
            fn(Type $type): string => $type->accept($this),
            $type->types,
        ));
    }

    public function intersection(IntersectionT $type): mixed
    {
        return implode('&', array_map(
            fn(Type $type): string => $type->accept($this),
            $type->types,
        ));
    }

    public function diff(DiffT $type): mixed
    {
        return \sprintf('%s\%s', $type->minuend->accept($this), $type->subtrahend->accept($this));
    }

    public function default(Type $type): mixed
    {
        /** @var non-empty-string */
        return serialize($type);
    }

    /**
     * @return non-empty-string
     */
    private function escapeStringLiteral(string $literal): string
    {
        /** @var non-empty-string */
        return str_replace("\n", '\n', var_export($literal, return: true));
    }
}
