<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Primitives\Collection\Collection;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MetaRouter
 * @package ObjectivePHP\Router
 */
class MetaRouter implements RouterInterface
{
    /**
     * @var Collection
     */
    protected $routers;

    /**
     * MetaRouter constructor.
     * @param array $routers
     */
    public function __construct(RouterInterface ...$routers)
    {

        $this->routers = Collection::cast($routers);
    }


    /**
     * @param RouterInterface $router
     */
    public function registerRouter(RouterInterface $router)
    {
        $this->routers->prepend($router);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRegisteredRouters()
    {
        return $this->routers;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return RoutingResult
     */
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult
    {
        $matchedRoute = null;

        $routingResult = new RoutingResult();

        /** @var RouterInterface $router */
        foreach ($this->routers as $router) {
            $routingResult = $router->route($request, $handler);
            if ($routingResult->didMatch()) {
                break;
            }
        }

        return $routingResult;
    }

    /**
     * @param $route
     * @param array $params
     * @return null
     */
    public function url($route, $params = [])
    {
        /** @var RouterInterface $router */
        foreach ($this->routers as $router) {
            if ($url = $router->url($route, $params)) {
                return $url;
            }
        }

        return null;
    }
}
