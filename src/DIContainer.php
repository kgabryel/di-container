<?php

namespace Frankie\DIContainer;

use Ds\Map;
use InvalidArgumentException;
use OutOfBoundsException;
use ReflectionClass;
use ReflectionException;

class DIContainer
{
    private static DIContainer $instance;
    private Map $objects;

    private function __construct()
    {
        $this->objects = new Map();
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new DIContainer();
        }
        return self::$instance;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        if (!$this->objects->hasKey($name)) {
            throw new OutOfBoundsException("Undefined key $name.");
        }
        return $this->objects->get($name);
    }

    public function hasKey($name): bool
    {
        return $this->objects->hasKey($name);
    }

    /**
     * @param string $name
     *
     * @throws ReflectionException
     * @throws DIContainerException
     */
    public function setNewObject(string $name): void
    {
        if (!class_exists($name)) {
            throw new InvalidArgumentException("Unknown class name $name.");
        }
        $this->objects->put($name, $this->getObject($name));
    }

    public function setExistsObject(object $object): void
    {
        if (!\is_object($object)) {
            throw new InvalidArgumentException('Parameter must be a object type.');
        }
        $this->objects->put(\get_class($object), $object);
    }

    /**
     * @param string $name
     *
     * @return object
     * @throws DIContainerException
     * @throws ReflectionException
     */
    private function getObject(string $name): object
    {
        if ($this->objects->hasKey($name)) {
            return $this->objects->get($name);
        }
        $objectParams = [];
        $info = new ObjectInfo($name);
        if (\count($info->getParams()) > 0) {
            foreach ($info->getParams() as $param) {
                $objectParams[] = $this->getObject($param);
            }
        }
        return $this->createObject($name, $objectParams);
    }

    /**
     * @param string $name
     * @param array $args
     *
     * @return object
     * @throws ReflectionException
     */
    private function createObject(string $name, array $args): object
    {
        $objectReflection = new ReflectionClass($name);
        $object = $objectReflection->newInstanceArgs($args);
        $this->setExistsObject($object);
        return $object;
    }
}
