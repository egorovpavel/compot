<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/28/13
 * Time: 11:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

use compot\IModel;

class TestModelClass implements DependencyInterface, IModel
{
    public $prop;
    /**
     * @var \DateTime
     */
    protected $prop1;

    /**
     * @var CustomTypeClass
     */
    protected $prop2;

    /**
     * @param mixed $prop1
     */
    public function setProp1($prop1)
    {
        $this->prop1 = $prop1;
    }

    /**
     * @return mixed
     */
    public function getProp1()
    {
        return $this->prop1;
    }

    /**
     * @param CustomTypeClass $prop2
     */
    public function setProp2($prop2)
    {
        $this->prop2 = $prop2;
    }

    /**
     * @return CustomTypeClass
     */
    public function getProp2()
    {
        return $this->prop2;
    }
}