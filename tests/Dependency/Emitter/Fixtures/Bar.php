<?php

declare(strict_types=1);

namespace Foo;

use function header;
use function setcookie;

function test(?SomeParam $someParam, ?bool $lala): ?SomeClass
{
}

function testAnonymousClass()
{
    test(null, null);
    header("aaa");
    setcookie('aaa', 0);
}
