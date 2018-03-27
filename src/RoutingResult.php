<?php

namespace ObjectivePHP\Router;


class RoutingResult
{
    /**
     * @var null|MatchedRoute
     */
    protected $matchedRoute;

    /**
     * RoutingResult constructor.
     * @param MatchedRoute|null $matchedRoute
     */
    public function __construct(MatchedRoute $matchedRoute = null)
    {
        if ($matchedRoute) $this->matchedRoute = $matchedRoute;
    }

    /**
     * @return bool
     */
    public function didMatch(): bool
    {
        return (bool)$this->matchedRoute;
    }

    /**
     * @return MatchedRoute
     */
    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

}