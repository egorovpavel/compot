<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/6/13
 * Time: 7:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC\Converters;


use PVC\IConverter;

class DefaultConverter implements IConverter
{

    public function convert(\ReflectionClass $type, $value)
    {
        return $type->newInstanceArgs([$value]);
    }

}