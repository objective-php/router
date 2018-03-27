<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Primitives\Collection\Collection;
use ObjectivePHP\Router\Exception\RoutingException;


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

    public function __construct($routers = [])
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
     * @param ApplicationInterface $app
     * @throws RoutingException
     */
    public function route(ApplicationInterface $app): RoutingResult
    {
        if (!$this->routers) {
            throw new RoutingException('Unable to route request: no router has been registered.', 500);
        }

        $matchedRoute = null;

        /** @var RouterInterface $router */
        foreach ($this->routers as $router) {
            $routingResult = $router->route($app);
            if ($routingResult->didMatch()) {
                break;
            }
        }

        if (!$routingResult->didMatch()) {
            throw new RoutingException('Unable to route request: no route matched requested URL', 404);
        }

        return $routingResult;

    }

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