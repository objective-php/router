<?php


namespace ObjectivePHP\Router\Cli;


use League\CLImate\CLImate;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use ObjectivePHP\Cli\Action\Parameter\Argument;
use ObjectivePHP\Cli\Action\Parameter\Param;
use ObjectivePHP\Router\Config\ActionNamespace;
use ObjectivePHP\Router\Exception\RoutingException;

/**
 * Class GenerateAction
 * @package ObjectivePHP\Router\Cli
 */
class GenerateAction extends AbstractCliAction
{

    /**
     * GenerateAction constructor.
     * @throws \ObjectivePHP\Cli\Action\Parameter\ParameterException
     */
    public function __construct()
    {

        $this->setCommand('generate-action');
        $this->setDescription('Generate a new action in the default action namespace.');

        $this->expects(new Argument('path', 'Path of the action to generate code for', Param::MANDATORY));

    }

    /**
     * @param ApplicationInterface $app
     * @return mixed|void
     * @throws RoutingException
     * @throws \ObjectivePHP\Config\Exception\ConfigException
     * @throws \ObjectivePHP\Config\Exception\ParamsProcessingException
     * @throws \ObjectivePHP\Cli\Action\CliActionException
     */
    public function run(ApplicationInterface $app)
    {
        $config = $app->getConfig();

        $actionNamespaces = $config->get(ActionNamespace::KEY);

        if (count($actionNamespaces) >= 1) {
            $actionNamespace = $actionNamespaces[0];
        } else {
            throw new RoutingException('Action generator cannot handle multiple action namesapces at this time');
        }

        $action = $this->getParam('action');
        $classToGenerate = $this->pathToClass($action, $actionNamespace);

        $c = new CLImate();

        $c->out('Classname: ' . $classToGenerate);

    }

    protected function pathToClass($path, $prefix)
    {
        $class = rtrim($prefix . '\\', '\\');

        $parts = explode('/', $path);
    }

}
