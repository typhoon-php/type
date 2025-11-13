<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type;
use Typhoon\Type\Visitor;

/**
 * @api
 * @template-covariant TResult
 * @extends Fallback<TResult>
 */
final class WeakVisitor extends Fallback
{
    /**
     * @var \WeakReference<Visitor<TResult>>
     */
    private readonly \WeakReference $visitor;

    /**
     * @param Visitor<TResult> $visitor
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitor = \WeakReference::create($visitor);
    }

    protected function fallback(Type $type): mixed
    {
        return $type->accept($this->visitor->get() ?? throw new \LogicException('Visitor is gone'));
    }
}
