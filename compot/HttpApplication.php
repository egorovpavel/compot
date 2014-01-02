<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/9/13
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;

class HttpApplication
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;
    /**
     * @var HttpKernel
     */
    protected $kernel;
    /**
     * @var DIContainer
     */
    protected $container;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var ControllerResolverInterface
     */
    protected $resolver;
    /**
     * @var IViewEngine
     */
    protected $viewEngine;

    public function __construct ()
    {
        $this->container = new DIContainer();
        $this->router    = new Router();
        $this->resolver  = new ControllerResolver($this->container, $this->router);
    }

    public function setViewEngine ($class)
    {
        $this->container->bindTo ('compot\\IViewEngine', DependencyBinder::to ($class));
    }

    public function setControllerPath ($path)
    {
        $this->resolver->setControllerPath ($path);
    }

    public function mapRoute ($name, $rule, array $defaults = null, $acceptNull = false)
    {
        $this->router->mapRoute ($name, $rule, $defaults, $acceptNull);
    }

    /**
     * @param  Request $request
     *
     * @return Response
     */
    public function run (Request $request = null)
    {
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber ($this->resolver);
        $this->kernel = new HttpKernel($this->dispatcher, $this->resolver);
        $this->container->bind ($request
            ? : Request::createFromGlobals ());
        $this->container->bind (Response::create ());
        $this->container->bindTo ("compot\\IValueProvider", DependencyBinder::to ("compot\\DefaultValueProvider"));
        $this->container->setModelBinder ($this->container->create ("compot\\DefaultModelBinder"));
        $request = $this->container->create ("Symfony\\Component\\HttpFoundation\\Request");

        $result = $this->kernel->handle ($request);
        $result->send ();

        return $result;
    }

    public function bind ($obj)
    {
        $this->container->bind ($obj);
    }

    public function getInstanceOf ($class)
    {
        return $this->container->create ($class);
    }

    public function getRoute ($name)
    {
        return $this->router->getRoute ($name);
    }
}
