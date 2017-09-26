<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;
use Psr\Http\Message\RequestInterface;

interface RouterInterface
{
    public function route(ApplicationInterface $app) : RoutingResult;

    // This is required but it will introduce a BC break
    //public function routeRequest(RequestInterface $request) : RoutingResult;

    public function url($route, $params = []);
}