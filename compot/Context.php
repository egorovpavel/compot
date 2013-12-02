<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/9/13
 * Time: 2:15 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Context
{

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(Request $request, Response $response, Router $router){
        $this->request = $request;
        $this->response = $response;
        $this->router = $router;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

}