<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/9/13
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class HttpApplication {

    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * @var Context
     */
    protected $context;


    public function __construct(){
        $this->container = new DIContainer();
        $this->container->bind(Request::createFromGlobals());
        $this->container->bind(Response::create());
        $this->container->bindTo("PVC\\IValueProvider",DependencyBinder::to("PVC\\DefaultValueProvider"));
        $this->container->setModelBinder($this->container->create("PVC\\DefaultModelBinder"));
        $this->router = new Router();
    }

    public abstract function initialize();

    public function run(){
        $this->initialize();
        $request = $this->container->create("Symfony\\Component\\HttpFoundation\\Request");
        $route = $this->router->match($request->getUri());
        $this->container->bind($route);
        $this->context = $this->container->create("PVC\\Context");
    }
}