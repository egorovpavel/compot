<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 9:02 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

class DefaultModelBinder implements IModelBinder
{

    /**
     * @var IValueProvider
     */
    private $valueProvider;

    public function __construct(IValueProvider $valueProvider)
    {
        $this->valueProvider = $valueProvider;
    }

    public function resolve($prefix, $object)
    {
        $reflected = new \ReflectionClass($object);
        $properties = $reflected->getProperties();
        foreach ($properties as &$property) {
            if ($this->isSettable($property, $reflected)) {
                $this->setValue($prefix, $reflected, $property, $object);
            }
        }
        return $object;
    }

    protected function resolveInstance($prefix, \ReflectionClass $type)
    {
        return $this->resolve($prefix, $type->newInstanceWithoutConstructor());
    }

    protected function isSettable(\ReflectionProperty $property, \ReflectionClass $class)
    {
        if ($property->isPublic()) {
            return true;
        }
        return $this->hasSetter($property, $class);
    }

    protected function hasSetter(\ReflectionProperty $property, \ReflectionClass $class)
    {
        return $class->hasMethod("set" . ucfirst($property->getName()));
    }

    protected function setValue($prefix, \ReflectionClass $class, \ReflectionProperty $property, $obj)
    {
        $methodName = "set" . ucfirst($property->getName());
        if ($class->hasMethod($methodName)) {
            $method = $class->getMethod($methodName);
            $parameter = reset($method->getParameters());
            /**
             * @var \ReflectionClass
             */
            $type = $parameter->getClass();
            if ($type instanceof \ReflectionClass && $type->implementsInterface("compot\\IModel")) {
                $prefix[] = $property->getName();
                $value = $this->resolveInstance($prefix, $type);
            } else {
                $value = $this->valueProvider->getValue($prefix, $property->getName(), $type);
            }
            $method->invoke($obj, $value);
        } else {
            $property->setValue($obj, $this->valueProvider->getValue($prefix, $property->getName()));
        }
    }

    /**
     * @return IValueProvider
     */
    public function getValueProvider(){
        return $this->valueProvider;
    }
}