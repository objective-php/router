<?php
/**
 * Created by PhpStorm.
 * User: gauthier
 * Date: 01/06/2016
 * Time: 17:20
 */

namespace ObjectivePHP\Router;


use ObjectivePHP\Primitives\Collection\Collection;
use Psr\Http\Server\MiddlewareInterface;

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
     * @var MiddlewareInterface
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
    public function __construct(string $name, MiddlewareInterface $action, $params = [])
    {
        $this->name = $name;
        $this->action = $action;
        $this->params = $params;
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

}