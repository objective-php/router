<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Middleware\AbstractMiddleware;
use ObjectivePHP\Message\Request\HttpRequest;
use ObjectivePHP\Primitives\Collection\Collection;
use Psr\Http\Message\RequestInterface;

/**
 * Class MetaRouter
 * @package ObjectivePHP\Router
 */
class MetaRouter extends AbstractMiddleware
{
    /**
     * @var Collection $routers
     */
    protected $routers;

    /**
     * MetaRouter constructor.
     * @param array $routers
     */
    public function __construct($routers = [])
    {
        $this->routers = Collection::cast($routers);
    }


    /**
     * @param RouterInterface $router
     * @return $this
     */
    public function register(RouterInterface $router)
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
     * @throws Exception
     */
    public function run(ApplicationInterface $app)
    {
        if (!$this->routers) {
            throw new Exception('Unable to route request: no router has been registered');
        }

        $matchedRoute = null;
        /** @var RouterInterface $router */
        foreach ($this->routers as $router) {
            $routingResult = $router->route($app);
            if ($routingResult->didMatch()) {
                $matchedRoute = $routingResult->getMatchedRoute();
                break;
            }
        }
        
        if (is_null($matchedRoute)) {
            throw new NoRouteFoundException();
        }

        if($app->getRequest() instanceof HttpRequest) {
            $app->getRequest()->getParameters()->setRoute($matchedRoute->getParams());
        }
        $app->getRequest()->setMatchedRoute($matchedRoute);
    }

    /**
     * Route a PSR-7 request.
     *
     * @param RequestInterface $request
     * @return RoutingResult
     * @throws Exception
     * @throws NoRouteFoundException
     */
    public function routeRequest(RequestInterface $request): RoutingResult
    {
        if(!$this->routers) {
            throw new Exception('Unable to route request: no router has been registered');
        }

        $matchedResult = null;
        foreach($this->routers as $router) {
            $routingResult = $router->routeRequest($request);

            if($routingResult->didMatch()) {
                $matchedResult = $routingResult;
                break;
            }
        }

        if (is_null($matchedResult)) {
            throw new NoRouteFoundException();
        }

        return $matchedResult;
    }
}