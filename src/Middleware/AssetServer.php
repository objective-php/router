<?php


namespace ObjectivePHP\Router\Middleware;


use function Couchbase\defaultDecoder;
use ObjectivePHP\Application\Middleware\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class AssetServer extends AbstractMiddleware
{

    protected $file;

    /**
     * AssetServer constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if(!is_file($this->file)) return new Response('File not found', 404);

        $extension = substr($this->file, strrpos($this->file, '.')+1);
        switch($extension) {

            case 'js':
                $type = 'application/javascript';
                break;

            case 'css':
                $type = 'text/css';
                break;

            default:
                $type = 'text/html';
                break;

        }

        $content = file_get_contents($this->file);
        $response = new Response();
        $response->getBody()->write($content);



        $response = $response->withHeader('Content-type', $type);

        return $response;

    }

}