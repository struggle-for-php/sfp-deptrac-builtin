<?php

declare(strict_types=1);

namespace SfpTest\Deptrac\Builtin\Dependency\Emitter;

use PHPUnit\Framework\TestCase;
use Sfp\Deptrac\Builtin\Dependency\Emitter\OptInBuiltinFunctionCallDependencyEmitter;

final class OptInBuiltinFunctionCallDependencyEmitterTest extends TestCase
{
    use EmitterTrait;

    public function testGetName(): void
    {
        self::assertSame('OptInBuiltinFunctionCallDependencyEmitter', (new OptInBuiltinFunctionCallDependencyEmitter())->getName());
    }

    public function testApplyDependencies(): void
    {
        $deps = $this->getEmittedDependencies(
            new OptInBuiltinFunctionCallDependencyEmitter(['header()', 'setcookie()']),
            __DIR__ . '/Fixtures/Bar.php'
        );

        self::assertCount(3, $deps);

        self::assertContains('Foo\testAnonymousClass():12 on Foo\test()', $deps);
        self::assertContains('Foo\testAnonymousClass():13 on header()', $deps);
        self::assertContains('Foo\testAnonymousClass():14 on Foo\setcookie()', $deps);
    }

    public function testApplyDependenciesWithoutFallback(): void
    {
        $deps = $this->getEmittedDependencies(
            new OptInBuiltinFunctionCallDependencyEmitter(['header()', 'setcookie()'], false),
            __DIR__ . '/Fixtures/Bar.php'
        );

        self::assertCount(2, $deps);

        self::assertContains('Foo\testAnonymousClass():12 on Foo\test()', $deps);
        self::assertContains('Foo\testAnonymousClass():13 on header()', $deps);
    }
}
