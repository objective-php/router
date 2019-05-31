<?php


namespace ObjectivePHP\Router\Router;


use ObjectivePHP\DebuggingTools\Middleware\AssetServer;
use ObjectivePHP\Router\MatchedRoute;
use ObjectivePHP\Router\RoutingResult;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetsRouter implements RouterInterface
{
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult
    {
        $path = $request->getUri()->getPath();
        $routingResult = new RoutingResult();
        if(strpos($path, 'assets/')) {
            $filePath = str_replace('/assets/debugbar/', 'vendor/maximebf/debugbar/src/DebugBar/Resources/', $path);
            $matchedRoute = new MatchedRoute('assets', new AssetServer(
                $filePath
            ));

            $routingResult = new RoutingResult($matchedRoute);
        }

        return $routingResult;
        
    }

    public function url($route, $params = [])
    {
        // TODO: Implement url() method.
    }

}