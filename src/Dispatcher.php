<?php

namespace ObjectivePHP\Router;


use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Middleware\AbstractMiddleware;
use ObjectivePHP\Application\View\Helper\Vars;
use ObjectivePHP\Invokable\Invokable;
use ObjectivePHP\Message\Response\HttpResponse;
use ObjectivePHP\Primitives\Collection\Collection;
use Zend\Diactoros\Response;

class Dispatcher extends AbstractMiddleware
{

    public function run(ApplicationInterface $app)
    {

        $matchedRoute = $app->getRequest()->getMatchedRoute();

        $action = Invokable::cast($matchedRoute->getAction());
        $app->getServicesFactory()->injectDependencies($action->getCallable());

        $app->setParam('runtime.action.middleware', $action);

        $result = $action->getCallable()($app);

        if($result instanceof Response)
        {
            $app->setResponse($result);
        }
        else
        {
            // set default content type
            $app->setResponse((new HttpResponse())->withHeader('Content-Type', 'text/html'));

            Collection::cast($result)->each(function ($value, $var)
            {
                Vars::set($var, $value);
            });
        }

    }
}