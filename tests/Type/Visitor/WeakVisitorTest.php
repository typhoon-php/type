<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;
use const Typhoon\Type\intT;
use const Typhoon\Type\objectT;
use const Typhoon\Type\stringT;

#[CoversClass(WeakVisitor::class)]
final class WeakVisitorTest extends TestCase
{
    #[TestWith([intT])]
    #[TestWith([stringT])]
    #[TestWith([objectT])]
    public function testItDecorates(Type $type): void
    {
        $stringify = new Stringify();
        $weakStringify = new WeakVisitor($stringify);

        $result = $type->accept($weakStringify);

        self::assertSame($type->accept($stringify), $result);
    }

    #[RunInSeparateProcess]
    public function testItAllowsForGarbageCollected(): void
    {
        gc_disable();
        $visitor = new /** @extends Fallback<null> */ class extends Fallback {
            protected function fallback(Type $type): null
            {
                return null;
            }
        };
        $weakRef = \WeakReference::create($visitor);
        $weakVisitor = new WeakVisitor($visitor);

        unset($visitor);

        self::assertNull($weakRef->get());

        $this->expectExceptionObject(new \LogicException('Visitor is gone'));

        stringT->accept($weakVisitor);
    }
}
