<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 8:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


interface IModelBinder
{
    public function __construct(IValueProvider $valueProvider);

    public function resolve($prefix, $object);
}