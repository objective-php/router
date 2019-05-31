<?php


namespace ObjectivePHP\Router\Config;


use ObjectivePHP\Config\Directive\AbstractMultiComplexDirective;

class AssetRoute extends AbstractMultiComplexDirective
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string
     */
    protected $substitution;

    /**
     * AssetRoute constructor.
     * @param string $pattern
     * @param $substitution
     */
    public function __construct(string $pattern, string $substitution)
    {
        $this->pattern = $pattern;
        $this->substitution = $substitution;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return string
     */
    public function getSubstitution(): string
    {
        return $this->substitution;
    }
}
