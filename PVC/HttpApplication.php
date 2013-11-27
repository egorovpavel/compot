<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/9/13
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpApplication{

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

    public function __construct(){
        $this->container = new DIContainer();
        $this->router = new Router();
        $this->resolver = new ControllerResolver($this->container,$this->router);
        $view = new ViewListener();
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($view);
        $this->kernel = new HttpKernel($this->dispatcher, $this->resolver);
    }

    public function setControllerPath($path){
        $this->resolver->setControllerPath($path);
    }

    public function mapRoute($name, $rule, array $defaults = null, $acceptNull = false){
        $this->router->mapRoute($name,$rule,$defaults,$acceptNull);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function run(Request $request = null){
        $this->container->bind($request ?: Request::createFromGlobals());
        $this->container->bind(Response::create());
        $this->container->bindTo("PVC\\IValueProvider",DependencyBinder::to("PVC\\DefaultValueProvider"));
        $this->container->setModelBinder($this->container->create("PVC\\DefaultModelBinder"));
        $request = $this->container->create("Symfony\\Component\\HttpFoundation\\Request");


        $result = $this->kernel->handle($request);
        $result->send();
        return $result;
    }
}