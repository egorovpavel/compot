<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/6/13
 * Time: 7:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\Converters;

use compot\IConverter;

class DefaultConverter implements IConverter
{

    public function convert(\ReflectionClass $type, $value)
    {
        return $type->newInstanceArgs([$value]);
    }

}