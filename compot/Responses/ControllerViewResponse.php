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
use Symfony\Component\HttpFoundation\Response;

class ControllerViewResponse implements IControllerResponse
{
    protected $templatePath;
    protected $data;

    /**
     * @param mixed $data
     */
    public function setData ($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData ()
    {
        return $this->data;
    }

    /**
     * @param mixed $templatePath
     */
    public function setTemplatePath ($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath ()
    {
        return $this->templatePath;
    }

    public function getResponse (CompotContext $context)
    {
        $viewEngine = $context->getContainer ()->create ("compot\\IViewEngine");
        if ( $this->getTemplatePath () ) {
            return new Response( $viewEngine->render ($this->getTemplatePath (), $this->getData ()) );
        }

        return new Response( $viewEngine->render ($context->getRoute ()->getTarget () . "/" . $context->getRoute ()->getAction (), $this->getData ()) );
    }
}
