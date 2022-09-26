<?php

declare(strict_types=1);

namespace SfpTest\Deptrac\Builtin\Dependency\Emitter;

use PHPUnit\Framework\TestCase;
use Sfp\Deptrac\Builtin\Dependency\Emitter\BuiltinFunctionCallDependencyEmitter;

final class BuiltinFunctionCallDependencyEmitterTest extends TestCase
{
    use EmitterTrait;

    public function testGetName(): void
    {
        self::assertSame('BuiltinFunctionCallDependencyEmitter', (new BuiltinFunctionCallDependencyEmitter())->getName());
    }

    public function testApplyDependencies(): void
    {
        $deps = $this->getEmittedDependencies(
            new BuiltinFunctionCallDependencyEmitter(),
            __DIR__.'/Fixtures/Bar.php'
        );

        self::assertCount(1, $deps);

        self::assertContains('Foo\testAnonymousClass():13 on header()', $deps);
    }
}