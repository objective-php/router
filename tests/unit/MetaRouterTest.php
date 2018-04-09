<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 01/06/2016
 * Time: 19:54
 */

namespace Test\ObjectivePHP\Router;


use Codeception\Test\Unit;
use ObjectivePHP\Primitives\Collection\Collection;
use ObjectivePHP\Router\Exception\RoutingException;
use ObjectivePHP\Router\MetaRouter;
use ObjectivePHP\Router\RouterInterface;
use ObjectivePHP\Router\RoutingResult;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MetaRouterTest extends Unit
{

    public function testFailsWhenRanWithoutRouters()
    {
        $metaRouter = new MetaRouter();

        $this->expectException(RoutingException::class);

        $metaRouter->route($this->makeEmpty(ServerRequestInterface::class), $this->makeEmpty(RequestHandlerInterface::class));
    }

    public function testFailsWhenNoRouterMatchesARoute()
    {
        $metaRouter = new MetaRouter();


        $routingResult = $this->make(RoutingResult::class, ['didMatch' => false]);
        $router = $this->makeEmpty(RouterInterface::class, ['route' => $routingResult]);

        $metaRouter->registerRouter($router);

        $this->expectException(RoutingException::class);
        $this->expectExceptionMessage('no route matched requested URL');

        $metaRouter->route($this->makeEmpty(ServerRequestInterface::class), $this->makeEmpty(RequestHandlerInterface::class));
    }


    public function testRoutersRegistration()
    {

        $metaRouter = new MetaRouter();

        $this->assertEquals(new Collection(), $metaRouter->getRegisteredRouters());

        $router1 = $this->makeEmpty(RouterInterface::class);
        $router2 = $this->makeEmpty(RouterInterface::class);

        $metaRouter->registerRouter($router1);

        $this->assertEquals(new Collection([$router1]), $metaRouter->getRegisteredRouters());

        $metaRouter->registerRouter($router2);

        $this->assertSame($router2, $metaRouter->getRegisteredRouters()->toArray()[0]);
        $this->assertSame($router1, $metaRouter->getRegisteredRouters()->toArray()[1]);

    }
}