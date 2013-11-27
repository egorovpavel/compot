<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/21/13
 * Time: 11:35 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tests;


use PVC\DefaultModelBinder;
use PVC\DefaultValueProvider;
use PVC\DependencyBinder;
use PVC\DIContainer;
use PVC\HttpApplication;
use PVC\tests\fixtures\CustomTypeClass;
use PVC\tests\fixtures\NoDependenciesClass;
use PVC\tests\fixtures\ResolvableDependencyClass;
use PVC\tests\fixtures\ResolvableModelDependencyClass;
use PVC\tests\fixtures\ResolvableModelHintedDependencyClass;
use PVC\tests\fixtures\ResolvableUnboundDependencyClass;
use PVC\tests\fixtures\TestHttpApplication;
use Symfony\Component\HttpFoundation\Request;

class DIContainerTest extends \PHPUnit_Framework_TestCase
{


    public function testContainerResolvesClassWithoutDependencies()
    {
        $container = new DIContainer();
        $this->assertTrue($container->create("PVC\\tests\\fixtures\\NoDependenciesClass") instanceof NoDependenciesClass);
    }

    public function testContainerWithBoundResolvableDependencies()
    {
        $container = new DIContainer();
        $request = Request::create("/testUrl");
        $container->bind($request);

        $obj = $container->create("PVC\\tests\\fixtures\\ResolvableDependencyClass");
        $this->assertNotNull($obj);
        $this->assertTrue($obj instanceof ResolvableDependencyClass);
        $this->assertTrue($obj->request instanceof Request);
    }


    public function testContainerWithUnBoundResolvableDependencies()
    {
        $container = new DIContainer();
        $request = Request::create("/testUrl");
        $container->bind($request);
        $container->bindTo('PVC\\tests\\fixtures\\DependencyInterface', DependencyBinder::to('PVC\\tests\\fixtures\\NoDependenciesClass'));

        $obj = $container->create("PVC\\tests\\fixtures\\ResolvableUnboundDependencyClass");
        $this->assertNotNull($obj);
        $this->assertTrue($obj instanceof ResolvableUnboundDependencyClass);
        $this->assertTrue($obj->dep instanceof ResolvableDependencyClass);
        $this->assertTrue($obj->dep2 instanceof NoDependenciesClass);
        $this->assertTrue($obj->idep instanceof NoDependenciesClass);
    }

    public function testContainerWithModelBinderResolvableDependencies()
    {
        $container = new DIContainer();
        $request = Request::create('/testUrl', 'GET', array("prop" => "propValue", "prop1" => "prop1Value", "prop2" => "prop2Value"));
        $container->setModelBinder(new DefaultModelBinder(new DefaultValueProvider($request)));
        $container->bindTo('PVC\\tests\\fixtures\\DependencyInterface', DependencyBinder::to('PVC\\tests\\fixtures\\TestModelClass'));
        $container->bind($request);

        $obj = $container->create("PVC\\tests\\fixtures\\ResolvableModelDependencyClass");
        $this->assertNotNull($obj);
        $this->assertTrue($obj instanceof ResolvableModelDependencyClass);

        $this->assertEquals($obj->model->prop, "propValue");
        $this->assertEquals($obj->model->getProp1(), "prop1Value");
        $this->assertEquals($obj->model->getProp2(), "prop2Value");

    }

    public function testContainerWithModelBinderResolvableTypeHintedDependencies()
    {
        $container = new DIContainer();
        date_default_timezone_set("UTC");
        $date = date("Y-m-d H:i:s");
        $params = array(
            "prop" => "propValue",
            "prop1" => $date,
            "prop2" => array(
                "id" => 12,
                "value" => $date
            )
        );
        $request = Request::create('/testUrl', 'GET', $params);
        $valueProvider = new DefaultValueProvider($request);
        $container->setModelBinder(new DefaultModelBinder($valueProvider));
        $container->bindTo('PVC\\tests\\fixtures\\DependencyInterface', DependencyBinder::to('PVC\\tests\\fixtures\\TestModelClass'));
        $container->bind($request);

        $obj = $container->create("PVC\\tests\\fixtures\\ResolvableModelHintedDependencyClass");
        $this->assertNotNull($obj);

        $this->assertTrue($obj instanceof ResolvableModelHintedDependencyClass);

        $this->assertEquals($obj->model->prop, "propValue");
        $this->assertTrue($obj->model->getProp1() instanceof \DateTime);
        $this->assertEquals($obj->model->getProp1(), new \DateTime($date));
        $this->assertTrue($obj->model->getProp2() instanceof CustomTypeClass);
        $this->assertEquals($obj->model->getProp2()->id, 12);
        $this->assertEquals($obj->model->getProp2()->getValue(), new \DateTime($date));

    }

    /**
     * @expectedException PVC\Exceptions\UnResolvableDependency
     */
    public function testContainerWithUnBoundUnresolvableNoTypeHintedDependencies()
    {
        $container = new DIContainer();
        $request = Request::create("/testUrl");
        $container->bind($request);

        $container->create("PVC\\tests\\fixtures\\UnresolvableNotHintedDependencyClass");
    }


    public function testHttpApplication(){
        date_default_timezone_set("UTC");
        $date = date("Y-m-d H:i:s");
        $params = array(
            "prop" => "propValue",
            "prop1" => $date,
            "prop2" => array(
                "id" => 12,
                "value" => $date
            )
        );
        $app = new HttpApplication();
        $app->mapRoute("test", "{controller}/{action}/{id}", [
            'controller' => 'Test',
            'action' => 'index',
            'id' => "defaultId"
        ]);
        $app->run(Request::create('/', 'GET', $params));
    }
}
