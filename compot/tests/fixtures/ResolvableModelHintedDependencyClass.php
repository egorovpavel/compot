<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 11:44 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

use Symfony\Component\HttpFoundation\Request;

class ResolvableModelHintedDependencyClass
{
    public $request;
    public $model;

    public function __construct(Request $request, TestModelHintedClass $model)
    {
        $this->request = $request;
        $this->model   = $model;
    }
}
