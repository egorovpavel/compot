<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/13/13
 * Time: 11:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tests;

use compot\DIContainer;
use compot\Route;
use compot\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testPreparesValidRegexpRules()
    {
        // no optional
        $this->assertEquals("/^\\/(?P<controller>[^\/]+)\/(?P<action>[^\/]+)\/(?P<id>[^\/]+)$/", Route::prepareRule("{controller}/{action}/{id}", null));
        // optional id
        $this->assertEquals("/^\\/(?P<controller>[^\/]+)\/(?P<action>[^\/]+)\/?(?P<id>[^\/]*)?$/", Route::prepareRule("{controller}/{action}/{id}", ['id' => "id"]));
        // optional action
        $this->assertEquals("/^\\/(?P<controller>[^\/]+)\/?(?P<action>[^\/]*)?\/?(?P<id>[^\/]*)?$/", Route::prepareRule("{controller}/{action}/{id}", ['id' => "id", 'action' => 'action']));
        // optional controller
        $this->assertEquals("/^\\/?(?P<controller>[^\/]*)?\/?(?P<action>[^\/]*)?\/?(?P<id>[^\/]*)?$/", Route::prepareRule("{controller}/{action}/{id}", ['id' => "id", 'action' => 'action', 'controller' => 'controller']));
    }

    public function testMatchesRouteRulesWithoutDefaultValues()
    {
        $router = new Router();
        $router->mapRoute("test", "{controller}/{action}/{id}");

        $actual = $router->match("/SomeController1/test/test_id");
        $this->assertEquals("SomeController1", $actual->getTarget());
        $this->assertEquals("test", $actual->getAction());
        $this->assertEquals("test_id", $actual->getArguments()['id']);

        $this->assertNull($router->match("/SomeController2/test/"));
        $this->assertNull($router->match("/SomeController3/test"));
        $this->assertNull($router->match("/SomeController4/"));
        $this->assertNull($router->match("/SomeController5"));
        $this->assertNull($router->match(""));
    }

    public function testMatchesRouteRulesWithDefaultArguments()
    {
        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}/{subid}/{someid}", [
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match("/SomeController1/test/test_id/test_subid/test_someid");
        $this->assertEquals("SomeController1", $actual->getTarget());
        $this->assertEquals("test", $actual->getAction());
        $this->assertEquals("test_id", $actual->getArguments()['id']);
        $this->assertEquals("test_subid", $actual->getArguments()['subid']);
        $this->assertEquals("test_someid", $actual->getArguments()['someid']);

        $actual = $router->match("/SomeController2/test/test_id/test_subid");
        $this->assertEquals("SomeController2", $actual->getTarget());
        $this->assertEquals("test", $actual->getAction());
        $this->assertEquals("test_id", $actual->getArguments()['id']);
        $this->assertEquals("test_subid", $actual->getArguments()['subid']);
        $this->assertEquals("default_someid", $actual->getArguments()['someid']);

        $actual = $router->match("/SomeController2/test");
        $this->assertEquals("SomeController2", $actual->getTarget());
        $this->assertEquals("test", $actual->getAction());
        $this->assertEquals("default_id", $actual->getArguments()['id']);
        $this->assertEquals("default_subid", $actual->getArguments()['subid']);
        $this->assertEquals("default_someid", $actual->getArguments()['someid']);

    }

    public function testMatchesRouteRulesWithDefaultAction()
    {
        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}/{subid}/{someid}", [
            'action' => 'default_action',
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match("/SomeController1/");
        $this->assertEquals("SomeController1", $actual->getTarget());
        $this->assertEquals("default_action", $actual->getAction());
        $this->assertEquals("default_id", $actual->getArguments()['id']);
        $this->assertEquals("default_subid", $actual->getArguments()['subid']);
        $this->assertEquals("default_someid", $actual->getArguments()['someid']);

    }

    public function testMatchesRouteRulesWithDefaultController()
    {
        $router = new Router(new DIContainer());
        $router->mapRoute("test", "fixedPrefix/{action}/{id}/{subid}/{someid}", [
            'controller' => 'default_controller',
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match("/fixedPrefix/test_action/test_id/test_subid");
        $this->assertEquals("default_controller", $actual->getTarget());
        $this->assertEquals("test_action", $actual->getAction());
        $this->assertEquals("test_id", $actual->getArguments()['id']);
        $this->assertEquals("test_subid", $actual->getArguments()['subid']);
        $this->assertEquals("default_someid", $actual->getArguments()['someid']);

        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}/{subid}/{someid}", [
            'controller' => 'default_controller',
            'action' => 'default_action',
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match("/");
        $this->assertEquals("default_controller", $actual->getTarget());
        $this->assertEquals("default_action", $actual->getAction());
        $this->assertEquals("default_id", $actual->getArguments()['id']);
        $this->assertEquals("default_subid", $actual->getArguments()['subid']);
        $this->assertEquals("default_someid", $actual->getArguments()['someid']);

    }

}
