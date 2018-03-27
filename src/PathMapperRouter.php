<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Router\Config\ActionNamespace;
use ObjectivePHP\Router\Config\UrlAlias;
use ObjectivePHP\Router\Exception\RoutingException;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Class PathMapperRouter
 *
 * This very basic router just maps the current URL to the route
 *
 * @package ObjectivePHP\Application\Operation\Common
 */
class PathMapperRouter implements RouterInterface
{
    public function route(ApplicationInterface $app): RoutingResult
    {
        $path = rtrim($app->getRequest()->getUri()->getPath(), '/');

        // default to home
        if (!$path) {
            $path = '/';
        }

        // check if path is routed
        $aliases = $app->getConfig()->get(UrlAlias::KEY);
        if ($aliases) {
            $path = $aliases[$path] ?? $path;
        }

        $action = null;

        // search for explicitly declared middleware
        if ($app->getServicesFactory()->has($path)) {
            $action = $app->getServicesFactory()->get($path);
        } else {

            $actionClass = $this->resolveActionClassName($path);

            $registeredActionNamespaces = $app->getConfig()->get(ActionNamespace::KEY);
            
            $actionFqcn = $this->resolveActionFullyQualifiedName($actionClass, $registeredActionNamespaces);

            if ($actionFqcn) {
                if ($app->getServicesFactory()->has($actionFqcn)) {
                    $action = $app->getServicesFactory()->get($actionFqcn);
                } else {
                    $action = new $actionFqcn;
                    $app->getServicesFactory()->injectDependencies($action);
                }
            }

        }

        if (!$action) {
            return new RoutingResult();
        }

        // check action is a Middleware
        if (!$action instanceof MiddlewareInterface) {
            throw new RoutingException('Service matching current route does not implement ' . MiddlewareInterface::class);
        }


        // return empty RoutingResult
        return new RoutingResult(new MatchedRoute($path, $action));

    }

    public function url($route, $params = [])
    {
        // TODO: Implement url() method.

        return null;
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
        //var_dump($registeredActionNamespaces);
        foreach ((array)$registeredActionNamespaces as $namespace) {
            $fullClassName = trim($namespace, '\\') . '\\' . $className;
            if (class_exists('\\' . $fullClassName)) {
                return $fullClassName;
            }
        }

        return null;
    }

}