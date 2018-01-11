<?php

namespace ObjectivePHP\Router;

/**
 * Class NoRouteFoundException
 * @package ObjectivePHP\Router
 */
class NoRouteFoundException extends Exception
{
    protected $message = "Unable to route request : no route matched requested URL";
    protected $code = 404;
}
