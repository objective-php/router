<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Primitives\Collection\Collection;
use ObjectivePHP\Router\Exception\RoutingException;
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
     * @param RequestHandlerInterface $handler
     * @return RoutingResult
     * @throws RoutingException
     */
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult
    {
        if ($this->routers->isEmpty()) {
            throw new RoutingException('Unable to route request: no router has been registered.', 500);
        }

        $matchedRoute = null;

        /** @var RouterInterface $router */
        foreach ($this->routers as $router) {
            $routingResult = $router->route($request, $handler);
            if ($routingResult->didMatch()) {
                break;
            }
        }

        if (!$routingResult->didMatch()) {
            throw new RoutingException('Unable to route request: no route matched requested URL', 404);
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