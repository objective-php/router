<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 01/06/2016
 * Time: 19:54
 */

namespace Test\ObjectivePHP\Router;


use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\PHPUnit\TestCase;
use ObjectivePHP\Primitives\Collection\Collection;
use ObjectivePHP\Router\Exception\RoutingException;
use ObjectivePHP\Router\MetaRouter;
use ObjectivePHP\Router\RouterInterface;
use ObjectivePHP\Router\RoutingResult;

class MetaRouterTest extends TestCase
{

    public function testFailsWhenRanWithoutRouters()
    {
        $metaRouter = new MetaRouter();

        $this->expectsException(function() use ($metaRouter)
        {

            $metaRouter->run($this->getMock(ApplicationInterface::class));
        },
            RoutingException::class
        );
    }

    public function testFailsWhenNoRouterMatchesARoute()
    {
        $metaRouter = new MetaRouter();

        $router = $this->getMock(RouterInterface::class);

        $routingResult = $this->getMock(RoutingResult::class);
        $routingResult->method('didMatch')->willReturn(false);

        $router->expects($this->once())->method('route')->willReturn($routingResult);

        $metaRouter->register($router);

        $this->expectsException(function() use ($metaRouter)
        {

            $metaRouter->run($this->getMock(ApplicationInterface::class));
        },
            RoutingException::class, 'no route matched requested URL'
        );
    }


    public function testRoutersRegistration()
    {

        $metaRouter = new MetaRouter();

        $this->assertEquals(new Collection(), $metaRouter->getRegisteredRouters());

        $router1 = $this->getMock(RouterInterface::class);
        $router2 = $this->getMock(RouterInterface::class);

        $metaRouter->register($router1);

        $this->assertEquals(new Collection([$router1]), $metaRouter->getRegisteredRouters());

        $metaRouter->register($router2);

        $this->assertSame($router2, $metaRouter->getRegisteredRouters()->toArray()[0]);
        $this->assertSame($router1, $metaRouter->getRegisteredRouters()->toArray()[1]);

    }
}