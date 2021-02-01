<?php

namespace Frankie\DIContainer;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class ObjectInfo
{
    private ReflectionClass $class;
    /** @var mixed[] */
    private array $params;

    /**
     *
     * @param string $name
     *
     * @throws ReflectionException
     * @throws DIContainerException
     */
    public function __construct(string $name)
    {
        if (!class_exists($name)) {
            throw new InvalidArgumentException("Unknown class name $name.");
        }
        $this->class = new ReflectionClass($name);
        $this->params = $this->setParams();
    }

    /**
     * @return mixed[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return mixed[]
     * @throws DIContainerException
     * @throws ReflectionException
     */
    private function setParams(): array
    {
        if (!$this->class->hasMethod('__construct')) {
            return [];
        }
        $method = $this->class->getMethod('__construct');
        $params = $method->getParameters();
        $objectParams = [];
        foreach ($params as $param) {
            if ($param->getClass() === null && !$param->isDefaultValueAvailable()) {
                throw new DIContainerException(
                    "Class {$this->class->name} in __constructor require non object 
                    variable which can't be injected."
                );
            }
            if (!$param->isDefaultValueAvailable()) {
                $objectParams[] = $param->getClass()
                    ->getName();
            }
        }
        return $objectParams;
    }
}
