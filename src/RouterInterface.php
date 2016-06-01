<?php

namespace ObjectivePHP\Router;

use ObjectivePHP\Application\ApplicationInterface;

interface RouterInterface
{
    public function route(ApplicationInterface $app) : RoutingResult;

    public function url($route, $params = []);
}