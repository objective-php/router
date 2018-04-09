<?php

namespace ObjectivePHP\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface RouterInterface
{
    public function route(ServerRequestInterface $request, RequestHandlerInterface $handler): RoutingResult;

    public function url($route, $params = []);
}