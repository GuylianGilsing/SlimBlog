<?php
namespace App;

/*

    Register your web routes here.
    This can be done with $app.

    For more info, visit:
    http://www.slimframework.com/docs/v4/objects/routing.html

*/
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthorizedMiddleware;
use App\Controllers\PageController;
use App\Controllers\UserController;

$app->get('/', PageController::class.':displayHomePage');
$app->get('/logout', UserController::class.':logout');

$app->group('', function(RouteCollectorProxy $group){
    $group->get('/login', PageController::class.':displayLoginPage');
    $group->get('/dashboard', PageController::class.':displayDashboardPage');

    $group->post('/login', UserController::class.':handleLoginFormRequest');
})->add(AuthorizedMiddleware::class);