<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 1/2/14
 * Time: 10:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tests;


use compot\HttpApplication;
use Symfony\Component\HttpFoundation\Request;

class ControllerViewResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testReturnsValidResponse()
    {
        date_default_timezone_set("UTC");
        $date   = date("Y-m-d H:i:s");
        $params = array ("prop" => "propValue", "prop1" => $date, "prop2" => array ("id" => 12, "value" => $date));
        $app    = new HttpApplication();
        $app->setControllerPath('compot\\tests\\fixtures\\');
        $app->setViewEngine('compot\\DummyViewEngine');
        $app->mapRoute(
            "test",
            "/{controller}/{action}/{id}",
            ['controller' => 'Test', 'action' => 'index', 'id' => "defaultId"]
        );

        $resultRaw = $app->run(Request::create('/', 'GET', $params));
        $expected = "Array\n(\n    [0] => test\n)\n";
        $this->assertEquals($expected, $resultRaw->getContent());
    }
}
