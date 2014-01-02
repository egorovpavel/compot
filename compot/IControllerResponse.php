<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 12/29/13
 * Time: 3:54 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

use compot\CompotContext;
use Symfony\Component\HttpFoundation\Response;

interface IControllerResponse
{
    /**
     * @param $controller
     * @param $action
     *
     * @return Response
     */
    public function getResponse (CompotContext $context);
}
