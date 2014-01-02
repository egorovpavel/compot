<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/23/13
 * Time: 8:59 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

interface IValueProvider
{
    public function getValue ($prefix, $name, \ReflectionClass $type = null);
}
