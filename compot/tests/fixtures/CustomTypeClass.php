<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/4/13
 * Time: 9:26 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot\tests\fixtures;

use compot\IModel;

class CustomTypeClass implements IModel
{
    public $id;
    /**
     * @var \DateTime
     */
    protected $value;

    /**
     * @param \DateTime $value
     */
    public function setValue(\DateTime $value)
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getValue()
    {
        return $this->value;
    }
}