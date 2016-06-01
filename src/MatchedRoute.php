<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 01/06/2016
 * Time: 17:20
 */

namespace ObjectivePHP\Router;


use ObjectivePHP\Primitives\Collection\Collection;

/**
 * Class MatchedRoute
 * @package ObjectivePHP\Router
 */
class MatchedRoute
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $action;

    /**
     * @var Collection
     */
    protected $params;

    /**
     * @var RouterInterface
     */
    protected $router;


    /**
     * MatchedRoute constructor.
     * @param RouterInterface $router
     * @param string $name
     * @param $action
     * @param array $params
     */
    public function __construct(RouterInterface $router, string $name, $action, $params = [])
    {
        $this->router = $router;
        $this->name = $name;
        $this->action = $action;
        $this->params = Collection::cast($params);
    }

    /**
     * @return mixed
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter() : RouterInterface
    {
        return $this->router;
    }

    public function getParams() : Collection
    {
        return $this->params;
    }


}