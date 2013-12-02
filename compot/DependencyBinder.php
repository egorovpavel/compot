<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/21/13
 * Time: 11:45 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;


/**
 * Class DependencyBinder
 * @package compot
 */
class DependencyBinder
{
    /**
     * @var
     */
    private $target;
    /**
     * @var bool
     */
    private $singleton = FALSE;

    /**
     * @param $target
     */
    private function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * @param $target
     * @return DependencyBinder
     */
    public static function to($target)
    {
        $inst = new DependencyBinder($target);
        return $inst;
    }

    /**
     * @return $this
     */
    public function asSingleton()
    {
        $this->singleton = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSingleton()
    {
        return $this->singleton;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }
}