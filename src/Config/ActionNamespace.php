<?php

namespace ObjectivePHP\Router\Config;

use ObjectivePHP\Config\Directive\AbstractScalarDirective;
use ObjectivePHP\Config\Directive\MultiValueDirectiveInterface;
use ObjectivePHP\Config\Directive\MultiValueDirectiveTrait;

/**
 * Class ActionNamespace
 *
 * @package ObjectivePHP\Router\Config
 */
class ActionNamespace extends AbstractScalarDirective implements MultiValueDirectiveInterface
{
    use MultiValueDirectiveTrait;

    const KEY = 'router.action-namespace';

    protected $key = self::KEY;
}
