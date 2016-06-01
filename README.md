# Objective PHP / Router [![Build Status](https://secure.travis-ci.org/objective-php/matcher.png?branch=master)](http://travis-ci.org/objective-php/router)

## Library topic

Router provides in the same time a meta routing mechanism, aiming at making possible to mix several routers, and also the standard Objective PHP router.

## Concept


### Meta Router

A meta router is a simple middleware on which actual routers can be registered. When executed, this middleware will loop on all registered routers,
and stop looping as soon as one of the registered routers matches a route.

```php
$metaRouter = new MetaRouter();
$metaRouter->register(new PathMapperRouter);
$metaRouter->register(new CustomRouter);
```

  
### Routers

Routers have to implement ```ObjectivePHP\Router\RouterInterface``` to be allowed to get registered on the meta router.

This simple interface requires routers to implement ```route($app)``` and ```url($route, $params)``` methods, the first one to test current request against declared routes, the latter to build a URL from a route name and params.  

Method ```route($app)``` is expected to return an instance of ```RoutingResult``` while ```url($route, $params)``` has to return a string.
 
### Routing result interpretation
 
The MetaRouter knows when a Router actually matched a route if the ```didMatch()``` method of the ```RoutingResult``` object returns ```true```. 
 
## Matched route
 
A ```RoutingResult``` that did match is able to return a ```MatchedRoute``` instance, that will let know the rest of the application about what route matched, what action this means, what params comes with and what Router did match the route.

## Performance

Using several routers can help dealing with performance issue, since it allows to use advanced routes when needed only. Please note that the later a Router is registered, the higher is its priority in the routing loop.
 
 
