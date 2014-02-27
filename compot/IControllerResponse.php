<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 12/29/13
 * Time: 3:54 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

interface IControllerResponse
{
    /**
     * @param CompotContext $context
     *
     * @return mixed
     */
    public function getResponse(CompotContext $context);
}
