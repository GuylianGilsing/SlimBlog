<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controllers\BaseController;
use App\Core\Views\TwigView;
use App\Content\NavBarContent;
use App\Core\Sessions\FlashMessage;

class PageController extends BaseController
{
    public function displayHomePage(Request $request, Response $response)
    {
        $output = "";

        $view = new TwigView('home');

        $view->setRequest($request);
        $view->setContainer($this->container);

        $view->load();

        $output = $view->render();

        $response->getBody()->write($output);
        return $response;
    }

    public function displayLoginPage(Request $request, Response $response)
    {
        $output = "";
        $viewVariables = [];

        // Get the current flash messages
        $errorFlashMessage = FlashMessage::getExisting('login-error');
        if($errorFlashMessage !== null)
        {
            $messages = $errorFlashMessage->getKey('messages');
            $viewVariables['errors'] = $messages;
        }

        // Create and render the route
        $view = new TwigView('login');

        $view->setRequest($request);
        $view->setContainer($this->container);
        $view->withVariables($viewVariables);

        $view->load();

        $output = $view->render();

        $response->getBody()->write($output);
        return $response;
    }

    public function displayDashboardPage(Request $request, Response $response)
    {
        $output = "";

        $view = new TwigView('dashboard');

        $view->setRequest($request);
        $view->setContainer($this->container);

        $view->load();

        $output = $view->render();

        $response->getBody()->write($output);
        return $response;
    }
}