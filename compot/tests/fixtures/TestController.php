<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/25/13
 * Time: 9:15 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

use compot\ControllerTrait;
use Symfony\Component\HttpFoundation\Request;

class TestController
{
    use ControllerTrait;

    public function getIndexAction(Request $request, TestModelHintedClass $model, $id = null)
    {
        $this->bag = ['test'];

        return $this->view();
    }

}
