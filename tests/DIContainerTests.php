<?php

namespace Frankie\DIContainer\Tests;

use Frankie\DIContainer\DIContainer;
use Frankie\DIContainer\Tests\Classes\Class1;
use Frankie\DIContainer\Tests\Classes\Class2;
use Frankie\DIContainer\Tests\Classes\ClassWithEmptyConstruct;
use Frankie\DIContainer\Tests\Classes\ClassWithOptionalParameter;
use Frankie\DIContainer\Tests\Classes\ClassWithOptionalParameter2;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class DIContainerTests extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSetExistThrowException($param)
    {
        $this->expectException(InvalidArgumentException::class);
        $diContainer = DIContainer::getInstance();
        $diContainer->setExistsObject($param);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetException()
    {
        $this->expectException(OutOfBoundsException::class);
        $diContainer = DIContainer::getInstance();
        $diContainer->get(ClassWithEmptyConstruct::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGet()
    {
        $diContainer = DIContainer::getInstance();
        $diContainer->setExistsObject(new ClassWithEmptyConstruct());
        $this->assertInstanceOf(ClassWithEmptyConstruct::class, $diContainer->get(ClassWithEmptyConstruct::class));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetNewThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $diContainer = DIContainer::getInstance();
        $diContainer->setNewObject('InvalidClassName');
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetSimple()
    {
        $diContainer = DIContainer::getInstance();
        $diContainer->setNewObject(ClassWithEmptyConstruct::class);
        $this->assertInstanceOf(ClassWithEmptyConstruct::class, $diContainer->get(ClassWithEmptyConstruct::class));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetRecusrsive()
    {
        $diContainer = DIContainer::getInstance();
        $diContainer->setNewObject(Class2::class);
        $this->assertInstanceOf(ClassWithEmptyConstruct::class, $diContainer->get(ClassWithEmptyConstruct::class));
        $this->assertInstanceOf(Class1::class, $diContainer->get(Class1::class));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithDefault()
    {
        $diContainer = DIContainer::getInstance();
        $diContainer->setNewObject(ClassWithOptionalParameter::class);
        $this->assertInstanceOf(ClassWithOptionalParameter::class, $diContainer->get(ClassWithOptionalParameter::class));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithDefault2()
    {
        $diContainer = DIContainer::getInstance();
        $diContainer->setNewObject(ClassWithOptionalParameter2::class);
        $this->assertInstanceOf(ClassWithOptionalParameter2::class, $diContainer->get(ClassWithOptionalParameter2::class));
    }

    public function simpleTypesProvider()
    {
        return [
            [''],
            [null],
            [123],
            [123, 333],
            [true],
            [false],
        ];
    }
}
