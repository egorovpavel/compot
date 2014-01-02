<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/28/13
 * Time: 11:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\Responses;

use compot\CompotContext;
use compot\IControllerResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ControllerRedirectToActionResponse implements IControllerResponse
{
    protected $name;
    protected $controller;
    protected $action;
    protected $args = [];

    /**
     * @return mixed
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName ($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getArgs ()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs ($args)
    {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getAction ()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction ($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getController ()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController ($controller)
    {
        $this->controller = $controller;
    }

    public function getResponse (CompotContext $context)
    {
        return new RedirectResponse($context->getRouter ()->getRoute ($this->getName ())->generateFor ($this->getController (), $this->getAction (), $this->getArgs ()));
    }
}
