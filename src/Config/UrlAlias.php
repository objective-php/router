<?php
/**
 * This file is part of the Objective PHP project
 *
 * More info about Objective PHP on www.objective-php.org
 *
 * @license http://opensource.org/licenses/GPL-3.0 GNU GPL License 3.0
 */

namespace ObjectivePHP\Router\Config;


use ObjectivePHP\Config\Directive\AbstractScalarDirective;
use ObjectivePHP\Config\Directive\IgnoreDefaultInterface;
use ObjectivePHP\Config\Directive\MultiValueDirectiveInterface;
use ObjectivePHP\Config\Directive\MultiValuesHandlingTrait;

class UrlAlias extends AbstractScalarDirective implements MultiValueDirectiveInterface, IgnoreDefaultInterface
{

    const KEY = 'router.url-alias';

    use MultiValuesHandlingTrait;

    protected $key = self::KEY;

}
