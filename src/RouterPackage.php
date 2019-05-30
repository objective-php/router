<?php
/**
 * Created by PhpStorm.
 * User: gde
 * Date: 26/03/2018
 * Time: 16:09
 */

namespace ObjectivePHP\Router;


use ObjectivePHP\Application\ApplicationAccessorsTrait;
use ObjectivePHP\Application\Package\AbstractPackage;
use ObjectivePHP\Application\Package\PackageInterface;
use ObjectivePHP\Cli\Config\CliCommandsPaths;
use ObjectivePHP\Config\Config;
use ObjectivePHP\Config\ConfigAccessorsTrait;
use ObjectivePHP\Config\ConfigInterface;
use ObjectivePHP\Config\ConfigProviderInterface;
use ObjectivePHP\Middleware\Action\PhtmlAction\Config\PhtmlDefaultLayout;
use ObjectivePHP\Middleware\Action\PhtmlAction\Config\PhtmlLayoutPath;
use ObjectivePHP\Router\Config\ActionNamespace;
use ObjectivePHP\Router\Config\UrlAlias;
use ObjectivePHP\ServicesFactory\Specification\InjectionAnnotationProvider;

/**
 * Class RouterPackage
 * @package ObjectivePHP\Router
 */
class RouterPackage extends AbstractPackage implements InjectionAnnotationProvider
{

    use ApplicationAccessorsTrait;
    use ConfigAccessorsTrait;

    /**
     * @return ConfigInterface
     */
    public function getDirectives(): array
    {
        return [
            new UrlAlias(),
            new ActionNamespace($this->getApplication()->getEngine()->getProjectNamespace() . '\\Action')
        ];
    }

    public function getParameters(): array
    {
        return [
            UrlAlias::KEY => ['/' => 'Home'],
            CliCommandsPaths::KEY => ['router' => __DIR__ . '/Cli']
        ];
    }


}