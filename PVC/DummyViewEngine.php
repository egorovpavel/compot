<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 12/1/13
 * Time: 2:42 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


class DummyViewEngine implements IViewEngine{

    public function render($template, $data)
    {
        return "dummy";
    }
}