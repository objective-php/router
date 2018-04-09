<?php
/**
 * Created by PhpStorm.
 * User: gde
 * Date: 26/03/2018
 * Time: 17:23
 */

namespace ObjectivePHP\Router\Config;


use ObjectivePHP\Config\Directive\AbstractScalarDirective;
use ObjectivePHP\Config\Directive\MultiValueDirectiveInterface;
use ObjectivePHP\Config\Directive\MultiValuesHandlingTrait;

class ActionNamespace extends AbstractScalarDirective implements MultiValueDirectiveInterface
{
    const KEY = 'router.action-namespace';

    use MultiValuesHandlingTrait;

    protected $key = self::KEY;
}