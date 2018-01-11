<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Config\ActionNamespace;
use ObjectivePHP\Primitives\String\Str;
use ObjectivePHP\Router\Config\UrlAlias;

/**
 * Class PathMapperRouter
 *
 * This very basic router just maps the current URL to the route
 *
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


        $actionClass = $this->resolveActionClassName($path);

        $registeredActionNamespaces = $app->getConfig()->get(ActionNamespace::class);
        $action = $this->resolveActionFullyQualifiedName($actionClass, $registeredActionNamespaces);

        if(!$action)
        {
            return new RoutingResult();
        }


        // return empty RoutingResult
        return new RoutingResult(new MatchedRoute($this, $path, $action));
        
    }

    public function url($route, $params = [])
    {
        // TODO: Implement url() method.
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function resolveActionClassName($path)
    {

        // clean path name
        $path = Str::cast($path);
        $path->trim('/');

        $namespaces = $path->split('/');

        $namespaces->each(function (&$namespace)
        {
            $parts = explode('-', $namespace);
            array_walk($parts, function (&$part)
            {
                $part = ucfirst($part);
            });

            $namespace = implode('', $parts);
        });

        $backslash = '\\';

        $className = str_replace('\\\\', '\\', implode($backslash, $namespaces->toArray()));

        return $className;
    }

    /**
     * @param $className
     *
     * @return null|string
     */
    public function resolveActionFullyQualifiedName($className, $registeredActionNamespaces)
    {

        foreach((array) $registeredActionNamespaces as $namespace)
        {
            $fullClassName = $namespace . '\\' . $className;
            if(class_exists('\\' . $fullClassName))
            {
                return $fullClassName;
            }
        }

        return null;
    }

}