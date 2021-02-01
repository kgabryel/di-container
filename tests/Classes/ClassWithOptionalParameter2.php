<?php

namespace Frankie\DIContainer\Tests\Classes;

class ClassWithOptionalParameter2
{
    public function __construct(Class1 $class1, array $array = [])
    {
    }
}
