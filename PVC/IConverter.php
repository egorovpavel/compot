<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/6/13
 * Time: 7:44 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


interface IConverter
{
    public function convert(\ReflectionClass $type, $value);
}