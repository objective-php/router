<?php


namespace ObjectivePHP\Router\Router;


use ObjectivePHP\Router\MatchedRoute;
use ObjectivePHP\Router\Middleware\AssetServer;
use ObjectivePHP\Router\RoutingResult;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetsRouter implements RouterInterface
{
    /**
     * @var array
     */
    protected $routes = [];

    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult
    {
        $routingResult = new RoutingResult();


        if ($request->getUri()) {
            $path = $request->getUri()->getPath();

            if (strpos($path, '/assets/') === 0) {
                $filePath = str_replace('/assets/debugbar/', 'vendor/maximebf/debugbar/src/DebugBar/Resources/', $path);
                $matchedRoute = new MatchedRoute('assets', new AssetServer($filePath));

                $routingResult = new RoutingResult($matchedRoute);
            }
        }
        return $routingResult;

    }

    public function url($route, $params = [])
    {
        // TODO: Implement url() method.
    }


}
