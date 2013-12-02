<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/28/13
 * Time: 11:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


class ControllerResponse {
    protected $templatePath;
    protected $data;
    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $templatePath
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
}