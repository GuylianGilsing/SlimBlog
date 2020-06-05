<?php
namespace App;

/*

    Register your dependencies in this file.
    You can use the PHP-DI Container ($container) for that.

    For more info, visit:
    http://www.slimframework.com/docs/v4/concepts/di.html

*/

use Slim\Csrf\Guard;

// Register dependencies to inject later here...

// Register CSRF protection
$responseFactory = $app->getResponseFactory();
$container->set('csrf', fn() => new Guard($responseFactory));

$app->add('csrf');