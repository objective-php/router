<?php

namespace ObjectivePHP\Router\Router;

use ObjectivePHP\Config\ConfigProviderInterface;
use ObjectivePHP\Router\Config\ActionNamespace;
use ObjectivePHP\Router\Config\UrlAlias;
use ObjectivePHP\Router\Exception\RoutingException;
use ObjectivePHP\ServicesFactory\Exception\ServicesFactoryException;
use ObjectivePHP\ServicesFactory\ServicesFactoryProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ObjectivePHP\Router\RoutingResult;
use ObjectivePHP\Router\MatchedRoute;

/**
 * Class PathMapperRouter
 *
 * This very basic router just maps the current URL to the route
 *
 * @package ObjectivePHP\Application\Operation\Common
 */
class PathMapperRouter implements RouterInterface
{
    /**
     * @param RequestHandlerInterface $handler
     * @return RoutingResult
     * @throws RoutingException
     * @throws \ObjectivePHP\ServicesFactory\Exception\ServicesFactoryException
     */
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult
    {
        $path = rtrim($request->getUri()->getPath(), '/');

        // default to home
        if (!$path) {
            $path = '/';
        }

        // check if path is routed
        if ($handler instanceof ConfigProviderInterface) {
            $aliases = $handler->getConfig()->get(UrlAlias::KEY);
            if ($aliases) {
                $path = $aliases[$path] ?? $path;
            }
        }

        $action = null;

        // search for explicitly declared middleware
        if ($handler instanceof ServicesFactoryProviderInterface && $handler->getServicesFactory()->has($path)) {
            $action = $handler->getServicesFactory()->get($path);
        } else {
            $actionClass = $this->resolveActionClassName($path);

            $registeredActionNamespaces = $handler->getConfig()->get(ActionNamespace::KEY);

            $actionFqcn = $this->resolveActionFullyQualifiedName($actionClass, $registeredActionNamespaces);

            if ($actionFqcn) {
                if ($handler instanceof ServicesFactoryProviderInterface
                    && $handler->getServicesFactory()->has($actionFqcn)
                ) {
                    $action = $handler->getServicesFactory()->get($actionFqcn);
                } else {
                    $action = new $actionFqcn;
                }
            }
        }

        if (!$action) {
            return new RoutingResult();
        }

        // return empty RoutingResult
        return new RoutingResult(new MatchedRoute($path, $action));
    }

    public function url($route, $params = [])
    {
        return ltrim('/' . $route, '/');
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function resolveActionClassName($path)
    {
        // clean path name
        $path = trim($path, '/');

        $namespaces = explode('/', $path);

        foreach ($namespaces as &$namespace) {
            $parts = explode('-', $namespace);

            array_walk($parts, function (&$part) {
                $part = ucfirst($part);
            });

            $namespace = implode('', $parts);
        }

        $backslash = '\\';

        $className = str_replace('\\\\', '\\', implode($backslash, $namespaces));

        return $className;
    }

    /**
     * @param $className
     *
     * @return null|string
     */
    public function resolveActionFullyQualifiedName($className, $registeredActionNamespaces)
    {
        foreach ((array)$registeredActionNamespaces as $namespace) {
            $fullClassName = trim($namespace, '\\') . '\\' . $className;
            if (class_exists('\\' . $fullClassName)) {
                return $fullClassName;
            }
        }

        return null;
    }
}
