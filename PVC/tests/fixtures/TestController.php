<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/25/13
 * Time: 9:15 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC\tests\fixtures;


use Symfony\Component\HttpFoundation\Request;

class TestController {

    public function getIndexAction(Request $request, TestModelHintedClass $model, $id = null){
        var_dump($model);
        var_dump($id);
        return "ok";
    }

}