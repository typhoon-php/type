<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor\Fallback;
use Typhoon\Type\Visitor\Reduced;

/**
 * @internal
 * @extends Fallback<int>
 */
final class EvaluateBitmask extends Fallback
{
    use Reduced;

    public function __construct(
        private readonly Type $bitmask,
    ) {}

    #[\Override]
    public function intValueT(IntValueT $type): mixed
    {
        return $type->value;
    }

    #[\Override]
    public function unionT(UnionT $type): mixed
    {
        $mask = 0;

        foreach ($type->types as $t) {
            $mask |= $t->accept($this);
        }

        return $mask;
    }

    #[\Override]
    public function constantT(ConstantT $type): mixed
    {
        $value = $type->evaluate();

        if (\is_int($value)) {
            return $value;
        }

        $this->fallback($type);
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): mixed
    {
        $value = $type->evaluate();

        if (\is_int($value)) {
            return $value;
        }

        $this->fallback($type);
    }

    #[\Override]
    protected function fallback(Type $type): never
    {
        throw new \LogicException(\sprintf(
            'Unexpected type `%s` in `%s`',
            Type\stringify($type),
            Type\stringify($this->bitmask),
        ));
    }
}
