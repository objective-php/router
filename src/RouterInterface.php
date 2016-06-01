<?php

namespace ObjectivePHP\Router;

interface RouterInterface
{
    public function route($app) : RoutingResult;

    public function url($route, $params = []);
}