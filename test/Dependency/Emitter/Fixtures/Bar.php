<?php

namespace Foo;
use SomeUse;
use function header;

function test(?SomeParam $someParam, $lala): ?SomeClass
{
}

function testAnonymousClass() {
    test(null, null);
    header("aaa");
    setcookie('aaa', 0);
}