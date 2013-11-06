<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/13/13
 * Time: 11:20 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tests;


use PVC\DIContainer;
use PVC\Router;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testPreparesValidRegexpRules()
    {
        $router = new Router(new DIContainer());

        // no optional
        $this->assertEquals("/\\/(?P<controller>[^\/]+)\/(?P<action>[^\/]+)\/(?P<id>[^\/]+)/", $router->prepareRule("{controller}/{action}/{id}", null));
        // optional id
        $this->assertEquals("/\\/(?P<controller>[^\/]+)\/(?P<action>[^\/]+)\/?(?P<id>[^\/]*)?/", $router->prepareRule("{controller}/{action}/{id}", ['id' => "id"]));
        // optional action
        $this->assertEquals("/\\/(?P<controller>[^\/]+)\/?(?P<action>[^\/]*)?\/?(?P<id>[^\/]*)?/", $router->prepareRule("{controller}/{action}/{id}", ['id' => "id", 'action' => 'action']));
        // optional controller
        $this->assertEquals("/\\/?(?P<controller>[^\/]*)?\/?(?P<action>[^\/]*)?\/?(?P<id>[^\/]*)?/", $router->prepareRule("{controller}/{action}/{id}", ['id' => "id", 'action' => 'action', 'controller' => 'controller']));
    }

    public function testMatchesRouteRulesWithoutDefaultValues()
    {
        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}");

        $actual = $router->match(Request::create("/SomeController1/test/test_id"));
        $this->assertEquals("SomeController1", $actual['controller']);
        $this->assertEquals("test", $actual['action']);
        $this->assertEquals("test_id", $actual['arguments']['id']);

        $this->assertNull($router->match(Request::create("/SomeController2/test/")));
        $this->assertNull($router->match(Request::create("/SomeController3/test")));
        $this->assertNull($router->match(Request::create("/SomeController4/")));
        $this->assertNull($router->match(Request::create("/SomeController5")));
        $this->assertNull($router->match(Request::create("")));
    }

    public function testMatchesRouteRulesWithDefaultArguments()
    {
        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}/{subid}/{someid}", [
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match(Request::create("/SomeController1/test/test_id/test_subid/test_someid"));
        $this->assertEquals("SomeController1", $actual['controller']);
        $this->assertEquals("test", $actual['action']);
        $this->assertEquals("test_id", $actual['arguments']['id']);
        $this->assertEquals("test_subid", $actual['arguments']['subid']);
        $this->assertEquals("test_someid", $actual['arguments']['someid']);

        $actual = $router->match(Request::create("/SomeController2/test/test_id/test_subid"));
        $this->assertEquals("SomeController2", $actual['controller']);
        $this->assertEquals("test", $actual['action']);
        $this->assertEquals("test_id", $actual['arguments']['id']);
        $this->assertEquals("test_subid", $actual['arguments']['subid']);
        $this->assertEquals("default_someid", $actual['arguments']['someid']);

        $actual = $router->match(Request::create("/SomeController2/test"));
        $this->assertEquals("SomeController2", $actual['controller']);
        $this->assertEquals("test", $actual['action']);
        $this->assertEquals("default_id", $actual['arguments']['id']);
        $this->assertEquals("default_subid", $actual['arguments']['subid']);
        $this->assertEquals("default_someid", $actual['arguments']['someid']);

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

        $actual = $router->match(Request::create("/SomeController1/"));
        $this->assertEquals("SomeController1", $actual['controller']);
        $this->assertEquals("default_action", $actual['action']);
        $this->assertEquals("default_id", $actual['arguments']['id']);
        $this->assertEquals("default_subid", $actual['arguments']['subid']);
        $this->assertEquals("default_someid", $actual['arguments']['someid']);

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

        $actual = $router->match(Request::create("/fixedPrefix/test_action/test_id/test_subid"));
        $this->assertEquals("default_controller", $actual['controller']);
        $this->assertEquals("test_action", $actual['action']);
        $this->assertEquals("test_id", $actual['arguments']['id']);
        $this->assertEquals("test_subid", $actual['arguments']['subid']);
        $this->assertEquals("default_someid", $actual['arguments']['someid']);

        $router = new Router(new DIContainer());
        $router->mapRoute("test", "{controller}/{action}/{id}/{subid}/{someid}", [
            'controller' => 'default_controller',
            'action' => 'default_action',
            'id' => 'default_id',
            'subid' => 'default_subid',
            'someid' => 'default_someid'
        ]);

        $actual = $router->match(Request::create("/"));
        $this->assertEquals("default_controller", $actual['controller']);
        $this->assertEquals("default_action", $actual['action']);
        $this->assertEquals("default_id", $actual['arguments']['id']);
        $this->assertEquals("default_subid", $actual['arguments']['subid']);
        $this->assertEquals("default_someid", $actual['arguments']['someid']);

    }

}
