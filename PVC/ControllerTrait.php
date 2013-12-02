<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/28/13
 * Time: 11:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


trait ControllerTrait {
    protected $bag = [];

    private function getAction(){
        $trace = end(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,3));
        $matches = [];
        preg_match('/^(get|post|put|delete|patch|head)([a-zA-Z0-9_]*)Action$/',$trace['function'],$matches);
        $action = $matches[2];
        preg_match('/(.*)\\\([a-zA-Z0-9_]*)Controller$/',$trace['class'],$matches);
        $controller = $matches[2];
        return ['controller' => $controller, 'action' => $action];
    }

    public function view($template = null, $data = null){
        $calledAction = $this->getAction();

        $controllerResponse = new ControllerResponse();
        $controllerResponse->setTemplatePath($template ?: $calledAction['controller'].DIRECTORY_SEPARATOR.$calledAction['action']);
        $controllerResponse->setData($data ?: $this->bag);

        return $controllerResponse;
    }
}