<?php

namespace Frankie\DIContainer\Tests;

use DateTime;
use Frankie\DIContainer\DIContainerException;
use Frankie\DIContainer\ObjectInfo;
use Frankie\DIContainer\Tests\Classes\ClassWithCorrectParams;
use Frankie\DIContainer\Tests\Classes\ClassWithEmptyConstruct;
use Frankie\DIContainer\Tests\Classes\ClassWithNonObjectArgument;
use Frankie\DIContainer\Tests\Classes\ClassWithoutConstruct;
use Frankie\DIContainer\Tests\Classes\ClassWithoutType;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ObjectInfoTests extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstructor($param)
    {
        $this->expectException(InvalidArgumentException::class);
        $objectInfo = new ObjectInfo($param);
    }

    /**
     * @dataProvider emptyArrayProvider
     */
    public function testEmptyArray($param)
    {
        $objectInfo = new ObjectInfo($param);
        $this->assertEmpty($objectInfo->getParams());
    }

    /**
     * @dataProvider nonObjectProvider
     */
    public function testNonObject($name)
    {
        $this->expectException(DIContainerException::class);
        $objectInfo = new ObjectInfo($name);
    }

    public function testWithCorrectArgument()
    {
        $objectInfo = new ObjectInfo(ClassWithCorrectParams::class);
        $expected = [
            DateTime::class,
            ClassWithEmptyConstruct::class
        ];

        $this->assertEquals($expected, $objectInfo->getParams());
    }

    public function constructDataProvider()
    {
        return [
            ['string'],
            ['Frankie\Di\Tmp']
        ];
    }

    public function emptyArrayProvider()
    {
        return [
            [ClassWithoutConstruct::class],
            [ClassWithEmptyConstruct::class],
        ];
    }

    public function nonObjectProvider()
    {
        return [
            [ClassWithNonObjectArgument::class],
            [ClassWithoutType::class]
        ];
    }
}
