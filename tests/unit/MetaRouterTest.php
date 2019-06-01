<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 01/06/2016
 * Time: 19:54
 */

namespace Test\ObjectivePHP\Router;


use Codeception\Test\Unit;
use ObjectivePHP\Router\Router\MetaRouter;
use ObjectivePHP\Router\Router\RouterInterface;
use ObjectivePHP\Router\RoutingResult;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MetaRouterTest
 * @package Test\ObjectivePHP\Router
 */
class MetaRouterTest extends Unit
{


    /**
     * @throws \Exception
     */
    public function testMetaRouterDoesNotFailWhenNoRouterMatchesARoute()
    {
        $metaRouter = new MetaRouter();


        $routingResult = $this->make(RoutingResult::class, ['didMatch' => false]);
        $router = $this->makeEmpty(RouterInterface::class, ['route' => $routingResult]);

        $metaRouter->registerRouter($router);

        $routingResult = $metaRouter->route($this->makeEmpty(ServerRequestInterface::class),
            $this->makeEmpty(RequestHandlerInterface::class));

        $this->assertFalse($routingResult->didMatch());
    }


    /**
     * @throws \Exception
     */
    public function testRoutersRegistration()
    {

        $metaRouter = new MetaRouter();

        $router1 = $this->makeEmpty(RouterInterface::class);
        $router2 = $this->makeEmpty(RouterInterface::class);

        $metaRouter->registerRouter($router1);

        $this->assertCount(3, $metaRouter->getRegisteredRouters());

        $metaRouter->registerRouter($router2);

        $this->assertSame($router2, $metaRouter->getRegisteredRouters()->toArray()[0]);
        $this->assertSame($router1, $metaRouter->getRegisteredRouters()->toArray()[1]);

    }
}
