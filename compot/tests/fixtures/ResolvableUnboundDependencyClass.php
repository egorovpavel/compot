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

class ResolvableUnboundDependencyClass
{
    public $dep;
    public $dep2;
    public $idep;

    public function __construct(ResolvableDependencyClass $dep, NoDependenciesClass $dep2, DependencyInterface $idep)
    {
        $this->dep  = $dep;
        $this->dep2 = $dep2;
        $this->idep = $idep;
    }

    public function controller(Request $request, ResolvableDependencyClass $dep1, DependencyInterface $idep)
    {
        return $request && $dep1 && $idep;
    }
}
