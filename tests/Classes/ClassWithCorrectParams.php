<?php

namespace Frankie\DIContainer\Tests\Classes;

use DateTime;

class ClassWithCorrectParams
{
    public function __construct(DateTime $dateTime, ClassWithEmptyConstruct $classWithEmptyConstruct)
    {
    }
}
