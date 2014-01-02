<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 11:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

class NoDependenciesClass implements DependencyInterface
{
    public function __construct()
    {

    }
}