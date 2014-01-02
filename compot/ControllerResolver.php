<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/25/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

use compot\IControllerResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerResolver implements ControllerResolverInterface, EventSubscriberInterface
{
    /**
     * @var DIContainer
     */
    protected $container;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var Route
     */
    protected $matchedRoute;
    protected $dependencies;
    protected $path;

    /**
     * @param DIContainer $container
     * @param Router      $router
     */
    public function __construct(DIContainer $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => 'onKernelView',
        );
    }

    public function onKernelView(GetResponseForControllerResultEvent $event){
        $viewEngine = $this->container->create("compot\\IViewEngine");

        $context = new CompotContext($this->container, $this->matchedRoute, $this->router);

        if($viewEngine && $event->getControllerResult() instanceof IControllerResponse){
            $controllerResponse = $event->getControllerResult();
            $response = $controllerResponse->getResponse($context);
            $event->setResponse($response);
        }else if($viewEngine && is_string($event->getControllerResult())){
            $event->setResponse(new Response($event->getControllerResult()));
        }

    }

    public function setControllerPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the Controller instance associated with a Request.
     *
     * As several resolvers can exist for a single application, a resolver must
     * return false when it is not able to determine the controller.
     *
     * The resolver must only throw an exception when it should be able to load
     * controller but cannot because of some errors made by the developer.
     *
     * @param Request $request A Request instance
     *
     * @return mixed|Boolean A PHP callable representing the Controller,
     *                       or false if this resolver is not able to determine the controller
     *
     * @throws \InvalidArgumentException|\LogicException If the controller can't be found
     *
     * @api
     */
    public function getController(Request $request)
    {
        $this->matchedRoute = $this->router->match($request->getPathInfo());

        if (!$this->matchedRoute) {
            return false;
        }

        try {
            $controllerInstance = $this->container->create($this->path . $this->matchedRoute->getTarget() . 'Controller');
        } catch (\ReflectionException $e) {
            return false;
        }

        $actionName = strtolower($request->getMethod()) . ucfirst(strtolower($this->matchedRoute->getAction())) . 'Action';

        if (!method_exists($controllerInstance, $actionName)) {
            return false;
        }

        $args = $this->matchedRoute->getArguments();
        foreach ($args as $key => $value) {
            $request->query->set($key, $value);
        }

        $this->dependencies = $this->container->resolveDependenciesFor($controllerInstance, $actionName);

        return array($controllerInstance, $actionName);
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request    A Request instance
     * @param mixed   $controller A PHP callable
     *
     * @return array An array of arguments to pass to the controller
     *
     * @throws \RuntimeException When value for argument given is not provided
     *
     * @api
     */
    public function getArguments(Request $request, $controller)
    {
        return $this->dependencies;
    }
}