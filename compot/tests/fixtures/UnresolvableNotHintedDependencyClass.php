<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 11:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

class UnresolvableNotHintedDependencyClass
{
    public $dep;

    public function __construct ($var)
    {
        $this->dep = $var;
    }
}
