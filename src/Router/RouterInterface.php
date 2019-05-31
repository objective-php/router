<?php

namespace ObjectivePHP\Router\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ObjectivePHP\Router\RoutingResult;

interface RouterInterface
{
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult;

    public function url($route, $params = []);
}