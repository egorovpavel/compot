<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/13/13
 * Time: 11:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tests;

use Symfony\Component\HttpFoundation\Request;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testHttpApplicationWithDummyViewEngine ()
    {
        date_default_timezone_set ("UTC");
        $date   = date ("Y-m-d H:i:s");
        $params = array ( "prop" => "propValue", "prop1" => $date, "prop2" => array ( "id" => 12, "value" => $date ) );
        $app    = new HttpApplication();
        $app->setControllerPath ('compot\\tests\\fixtures\\');
        $app->setViewEngine ('compot\\DummyViewEngine');
        $app->mapRoute ("test", "/{controller}/{action}/{id}", [ 'controller' => 'Test', 'action' => 'index', 'id' => "defaultId" ]);
        $resultRaw = $app->run (Request::create ('/', 'GET', $params));

        $this->assertEquals ('dummy', $resultRaw->getContent ());
    }

}
