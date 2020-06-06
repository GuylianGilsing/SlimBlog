<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Core\Sessions\Session;
use App\Core\Sessions\FlashMessage;
use App\Core\Validation\RespectValidator;
use Respect\Validation\Validator as v;

class UserController extends BaseController
{
    private array $errorMessages = [
        'login' => [
            'general' => "Username of password is not valid."
        ]
    ];

    public function handleLoginFormRequest(Request $request, Response $response)
    {
        $output = '';
        $redirectLocation = ServerBase().'/login';

        // Get the POST body content
        $requestBody = $request->getParsedBody();

        // Validate the POST request fields
        $validation = new RespectValidator($request);
        $validation->setRules([
            'user_name' => v::stringType()
                            ->noWhitespace()
                            ->notEmpty()
                            ->length(1, 32),

            'user_password' => v::stringType()
                                ->noWhitespace()
                                ->notEmpty()
                                ->length(1, 512)
        ]);
        $validation->validate();

        // Make sure that the validation was valid
        if(!$validation->isValid())
        {
            return $response->withHeader('Location', ServerBase().'/login');
        }

        // Validation was valid, retrieve a user with the given username
        // from the database
        $user = UserModel::where('name', '=', $requestBody['user_name'])->get();

        // Make sure that a user is actually found
        if(count($user) > 0)
        {
            $user = $user[0];
            $password = $requestBody['user_password'];

            // Verify the password
            if(password_verify($password, $user->password))
            {
                // Create a new signature so we know the session has been
                // created by the server, and thus hasn't been forged
                $sessionSignature = hash_hmac(
                    'sha256',
                    $user->id.$user->name,
                    $_ENV['LOGIN_SIGNATURE']
                );

                // Create the user session
                $userSession = new Session('user');
                
                $userSession->setKey('id', $user->id);
                $userSession->setKey('signature', $sessionSignature);
                $userSession->setTimeout(60 * $_ENV['LOGIN_TIMEOUT']);

                $userSession->create();

                // Redirect to the /dashboard page
                $redirectLocation = ServerBase().'/dashboard';
                return $response->withHeader('Location', $redirectLocation);
            }

            $flashMessage = new FlashMessage('login-error');
            $flashMessage->setKey(
                'messages',
                $this->errorMessages['login']['general']
            );
            $flashMessage->create();

            return $response->withHeader('Location', $redirectLocation);
        }

        return $response->withHeader('Location', $redirectLocation);
    }

    public function logout(Request $request, Response $response)
    {
        $userSession = Session::getExisting('user');
        if($userSession !== null)
            $userSession->destroy();

        return $response->withHeader('Location', ServerBase().'/login');
    }
}