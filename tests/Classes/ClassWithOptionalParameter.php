<?php

namespace Frankie\DIContainer\Tests\Classes;

class ClassWithOptionalParameter
{
    public function __construct(Class1 $class1, Class2 $class2=null)
    {
    }
}
