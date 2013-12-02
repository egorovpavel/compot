<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/25/13
 * Time: 9:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewListener implements  EventSubscriberInterface{
    /**
     * @var IViewEngine
     */
    protected $viewEngine;

    public function __construct(IViewEngine $viewEngine = null){
        $this->viewEngine = $viewEngine;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event){
        if($this->viewEngine && $event->getControllerResult() instanceof ControllerResponse){
            $res = $this->viewEngine->render($event->getControllerResult()->getTemplatePath(), $event->getControllerResult()->getData());
            $event->setResponse(new Response($res));
        }else{
            $event->setResponse(new Response("hui2"));
        }
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
}