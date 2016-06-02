<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Config\UrlAlias;
use ObjectivePHP\Router\Config\SimpleRoute;

/**
 * Class PathMapperRouter
 *
 * This very basic router just maps the current URL to the route
 *
 * @deprecated
 * @package ObjectivePHP\Application\Operation\Common
 */
class PathMapperRouter implements RouterInterface
{
    public function route(ApplicationInterface $app) : RoutingResult
    {
        $path = rtrim($app->getRequest()->getUri()->getPath(), '/');
        
        // default to home
        if(!$path)
        {
            $path = '/';
        }
        
        // check if path is routed
        $aliases = $app->getConfig()->subset(UrlAlias::class);
        if($aliases)
        {
            $path = $aliases[$path] ?? $path;
        }
        
        // look for matching route
        $routes = $app->getConfig()->subset(SimpleRoute::class)->reverse();
        /** @var SimpleRoute $route */

        foreach($routes as $alias => $route)
        {
            if($route->matches($app->getRequest()))
            {
                return new RoutingResult(new MatchedRoute($this, $alias, $route->getAction()));
            }
        }

        // return empty RoutingResult
        return new RoutingResult();
        
    }

    public function url($route, $params = [])
    {
        // TODO: Implement url() method.
    }


}