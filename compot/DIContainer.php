<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/13/13
 * Time: 11:49 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

use compot\Exceptions\UnResolvableDependency;

/**
 * Class DIContainer
 *
 * @package compot
 */
class DIContainer
{
    /**
     * @var IModelBinder
     */
    protected $modelBinder = null;

    /**
     * @var array
     */
    protected $singletons = [];

    /**
     * @var Binder[]
     */
    protected $bindings = [];

    /**
     * @param $obj
     *
     * @return Binder
     */
    public function bind($obj)
    {
        $bind                            = DependencyBinder::to($obj);
        $this->bindings[get_class($obj)] = $bind;

        return $bind;
    }

    /**
     * @param string           $class
     * @param DependencyBinder $binder
     */
    public function bindTo($class, DependencyBinder $binder)
    {
        $this->bindings[$class] = $binder;
    }

    /**
     * @param IModelBinder $modelBinder
     */
    public function setModelBinder(IModelBinder $modelBinder)
    {
        $this->modelBinder = $modelBinder;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return $this|object
     */
    protected function getBound(\ReflectionClass $class)
    {
        $bind = isset($this->bindings[$class->getName()])
            ? $this->bindings[$class->getName()]
            : null;
        if (!$bind) {
            return $this->create($class->getName());
        }

        return is_object($bind->getTarget())
            ? $bind->getTarget()
            : $this->create($bind->getTarget());
    }

    /**
     * @param \ReflectionClass $class
     * @param array            $args
     *
     * @return object
     */
    protected function getInstance(\ReflectionClass $class, $args = array ())
    {
        if (isset($this->bindings[$class->getName()])) {
            return $this->getBound($class, $args);
        }

        return $class->newInstanceArgs($args);
    }

    /**
     * @param $class
     *
     * @return $this|object
     */
    public function create($class)
    {
        if ($class == get_class($this)) {
            return $this;
        }

        $reflector = new \ReflectionClass($class);

        $constructor = $reflector->getConstructor();

        if ($constructor && $constructor->getParameters() && !isset($this->bindings[$reflector->getName()])) {
            return $this->getInstance($reflector, $this->resolveDependencies($constructor));
        }

        $instance = $this->getInstance($reflector);
        if ($reflector->implementsInterface("compot\\IModel")) {
            $this->modelBinder->resolve([], $instance);
        }

        return $instance;
    }

    /**
     * @param object $obj
     * @param string $method
     *
     * @return mixed
     */
    public function invoke($obj, $method)
    {
        $reflector = new \ReflectionClass($obj);
        $method    = $reflector->getMethod($method);

        return $method->invoke($obj, $this->resolveDependencies($method));
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return array
     * @throws \Exception
     */
    protected function resolveDependencies(\ReflectionMethod $method)
    {
        $params       = $method->getParameters();
        $dependencies = [];

        foreach ($params as &$parameter) {
            if (($reflector = $parameter->getClass())) {
                $dependencies[] = $this->getBound($reflector)
                    ? : $this->create($reflector->getName());
            } elseif ($this->modelBinder) {
                $dependencies[] = $this->modelBinder->getValueProvider()->getValue([], $parameter->getName());
            } else {
                throw new UnResolvableDependency();
            }
        }

        return $dependencies;
    }

    public function resolveDependenciesFor($obj, $methodName)
    {
        $reflector = new \ReflectionClass($obj);
        $method    = $reflector->getMethod($methodName);

        return $this->resolveDependencies($method);
    }
}
