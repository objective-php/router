<?php


namespace ObjectivePHP\Router\Cli;


use Composer\Autoload\ClassLoader;
use League\CLImate\CLImate;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use ObjectivePHP\Cli\Action\Parameter\Argument;
use ObjectivePHP\Cli\Action\Parameter\Param;
use ObjectivePHP\Html\Tag\Tag;
use ObjectivePHP\Primitives\String\Camel;
use ObjectivePHP\Primitives\String\Snake;
use ObjectivePHP\Router\Config\ActionNamespace;
use ObjectivePHP\Router\Exception\RoutingException;
use ObjectivePHP\ServicesFactory\ServicesFactory;

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

        $c = new CLImate();

        if (count($actionNamespaces) >= 1) {
            $options = [];
            foreach ($actionNamespaces as $actionNamespace) {
                $options[] = rtrim($actionNamespace, '\\');
            }
            $input = $c->radio('Please select the namespace in which the action should be generated (defaults to ' . $options[0] . ') ', $options);

            $actionNamespace = $input->prompt() ?: $options[0];

        } else {
            throw new RoutingException('No action namespace detected.');
        }

        $action = $this->getParam('path');
        $classToGenerate = $this->pathToClass($action, $actionNamespace);

        /** @var ClassLoader $autoloader */
        $autoloader = $app->getEngine()->getAutoloader();

        $paths = $autoloader->getPrefixesPsr4();
        $matchingPaths = [];
        $options = [];
        foreach ($paths as $prefix => $path) {
            if (strpos($classToGenerate, $prefix) === 0) {
                $matchingPaths[$prefix] = $path;
                $options[] = $prefix;
            }
        }


        if (count($matchingPaths) == 1) {
            $prefix = $options[0];
            $data = $matchingPaths[$prefix];
        } else {
            // ask for user selected option
        }



        $classPath = $this->classFileName($classToGenerate, $prefix, $data[0]);

        $relativeClassPath = substr($classPath, strlen(getcwd()) + 1);


        $source = $this->generateClassSource($classToGenerate);

        $input = $c->confirm('Do you really want to generate class ' . $classToGenerate . ' in file ' . $relativeClassPath);
        $input->defaultTo('n');

        if ($input->confirmed()) {

            // create directory if needed
            $dirs = explode('/', $relativeClassPath);
            $file = array_pop($dirs);

            $cwd = getcwd();
            foreach ($dirs as $dir)
            {
                $cwd .= DIRECTORY_SEPARATOR . $dir;
                if(!is_dir($cwd)) mkdir($cwd);
            }

            if(!is_file($cwd . DIRECTORY_SEPARATOR . $file)) {

                // store class
                file_put_contents($relativeClassPath, $source);

                // store template
                file_put_contents(str_replace('.php', '.phtml', $relativeClassPath), $this->generateTemplateSource($classToGenerate));

                $c->green('Successfully wrote source template to new action in ' . $relativeClassPath);
            } else {
                $c->error('Did not overwrite existing file ' . $relativeClassPath);
            }


        } else {
            $c->info('Action generation aborted');
        }


    }



    protected function pathToClass($path, $prefix)
    {
        $class = rtrim($prefix . '\\', '\\');

        $parts = explode('/', $path);
        foreach ($parts as &$part) {

            $part = Camel::case($part, Camel::UPPER, '-');
        }
        $class .= implode('\\', $parts);

        return $class;
    }

    protected function classFileName($class, $prefix, $destination) {

        // remove th PSR-4 prefix
        $classPath = realpath($destination) . DIRECTORY_SEPARATOR . substr($class, strlen(trim($prefix, '\\') . '\\')) . '.php';
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $classPath);

        return $classPath;

    }

    protected function generateClassSource($className)
    {
        $lastNsSeparator = strrpos($className, '\\');
        $namespace = substr($className, 0, $lastNsSeparator);
        $classShortName = substr($className, $lastNsSeparator + 1);

    $code = <<<CODE
<?php
namespace $namespace;

use ObjectivePHP\PhtmlAction\PhtmlAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class $classShortName extends PhtmlAction
{
    public function process(ServerRequestInterface \$request, RequestHandlerInterface \$handler): ResponseInterface
    {
        // implement your logic here!
        
        return \$this->render([]);
    }
}

CODE;

    return $code;

    }


    public function generateTemplateSource($className) {
        return '<?php

        use ObjectivePHP\\Html\\Tag\\Tag;
        
        Tag::h1(\'Default template for action ' . $className . '\');
        
        ';
    }
}
