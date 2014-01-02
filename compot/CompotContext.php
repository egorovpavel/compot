<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 1/1/14
 * Time: 2:40 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

class CompotContext
{
    /**
     * @var DIContainer
     */
    protected $container;
    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(DIContainer $container, Route $route, Router $router)
    {
        $this->container = $container;
        $this->route     = $route;
        $this->router    = $router;
    }

    /**
     * @return \compot\DIContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \compot\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return \compot\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

}
