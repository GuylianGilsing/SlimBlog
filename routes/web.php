<?php
namespace App;

/*

    Register your web routes here.
    This can be done with $app.

    For more info, visit:
    http://www.slimframework.com/docs/v4/objects/routing.html

*/

use App\Controllers\HelloWorldController;

$app->get('/', HelloWorldController::class);