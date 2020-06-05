<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controllers\BaseController;
use App\Core\Views\TwigView;

class HelloWorldController extends BaseController
{
    public function __invoke(Request $request, Response $response)
    {
        $output = "";

        $view = new TwigView('helloworld');

        $view->setRequest($request);
        $view->setContainer($this->container);
        $view->withVariables(['slug' => "World"]);
        $view->load();

        $output = $view->render();

        $response->getBody()->write($output);
        return $response;
    }
}