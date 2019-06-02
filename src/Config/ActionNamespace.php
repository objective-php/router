<?php

namespace ObjectivePHP\Router\Config;

use ObjectivePHP\Config\Directive\AbstractScalarDirective;
use ObjectivePHP\Config\Directive\MultiValueDirectiveInterface;
use ObjectivePHP\Config\Directive\MultiValueDirectiveTrait;

/**
 * ActionNamespace
 *
 * The action namespaces are used by the default PathMapperRouter to find action classes matching request path.
 *
 * In the ObjectivePHP Starter Kit, the default action namespace is ```Project\Action``` located in ```app/src/Action``` .
 * This means that PathMapperRouter will test the existence of a class which name is formed with the action namespace and the request path:
 *
 * For a request path being ```/admin/users/list-all```, and using the default action namespace, the PathMapperRouter will
 * look for a class named ```Project\Action\Admin\Users\ListAll```. IF it exists, the PathMapperRouter will consider it can
 * route the request, and will set set ListAll class as middleware in charge of handling the request.
 *
 *
 * @config-example-reference 'api-actions'
 * @config-example-value 'Project\Api\Action'
 *
 * @package ObjectivePHP\Router\Config
 */
class ActionNamespace extends AbstractScalarDirective implements MultiValueDirectiveInterface
{
    use MultiValueDirectiveTrait;

    const KEY = 'router.action-namespace';

    protected $key = self::KEY;
}
