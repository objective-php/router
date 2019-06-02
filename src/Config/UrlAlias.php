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
use ObjectivePHP\Config\Directive\MultiValueDirectiveTrait;

/**
 * UrlAlias
 *
 * These aliases are used by the PathMapperRouter. When a requested path matches an alias key,
 * PathMapperRouter will try to find the action matching the alias.
 *
 * This is useful to alias '/' to '/home' (done by default byu objective-php/application), but it can also
 * help handling action moving for instance, or even small SEO concerns: if you only have a few public pages,
 * you can use aliases to handle multilingual paths (alias '/contactez-nous' => '/contact-us')
 *
 * @config-example-reference '/real-path'
 * @config-example-value '/aliased'
 *
 * @package ObjectivePHP\Router\Config
 */
class UrlAlias extends AbstractScalarDirective implements MultiValueDirectiveInterface, IgnoreDefaultInterface
{
    use MultiValueDirectiveTrait;

    const KEY = 'router.url-alias';

    protected $key = self::KEY;
}
