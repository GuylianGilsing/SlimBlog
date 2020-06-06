<?php
namespace App\Middleware;

use App\Core\Sessions\Session;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Psr7\Response;
use App\Middleware\BaseMiddleware;
use App\Models\UserModel;

class AuthorizedMiddleware extends BaseMiddleware
{
    private $validRoutes = [
        '/login',
        '/register'
    ];

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $curRoute = $request->getUri()->getPath();
        $redirectLocation = ServerBase().'/login';
        $response = new Response();

        // Get the user session
        $userSession = Session::getExisting('user');
        if($userSession === null && !in_array($curRoute, $this->validRoutes))
            return $response->withHeader('Location', $redirectLocation);

        // Allow the user to access the 'valid' routes
        if($userSession === null && in_array($curRoute, $this->validRoutes))
            return $handler->handle($request);

        // Get the user from the database
        $user = UserModel::where('id', '=', $userSession->getKey('id'))->get();
        if(count($user) <= 0)
            return $response->withHeader('Location', $redirectLocation);

        // Create a new signature to compare against the session signature
        $user = $user[0];
        $sessionSignature = $userSession->getKey('signature');
        $serverSignature = hash_hmac(
            'sha256',
            $user->id.$user->name,
            $_ENV['LOGIN_SIGNATURE']
        );

        // Make sure that the hash of the server and session signature matches
        if(!hash_equals($serverSignature, $sessionSignature))
            return $response->withHeader('Location', $redirectLocation);

        // Make sure that the user can't access the 'valid' routes
        if(in_array($curRoute, $this->validRoutes))
        {
            $redirectLocation = ServerBase().'/dashboard';
            return $response->withHeader('Location', $redirectLocation);
        }

        return $handler->handle($request);
    }
}