# SlimApplication
A simple boilerplate PHP app that makes uses of the Slim 4 framework and various other packages.

This boilerplate comes with the following:
* Composer autoloading
* .env file loading
* Laravel eloquent ORM
* PHP-DI dependency container
* Slim 4 base project
* Slim (global) CSRF protection
* Custom content tools class
* Custom session class
* Custom flash message class
* Custom error handling class (using respect/validation)
* Gulp
* Sass (.scss files) frontend workflow

# Table of contents
- [Installation](#installation)
- [Installed packages and dependencies](#installed-packages-and-dependencies)
    - [Backend packages](#backend-packages)
        - [Vlucas/PHPDotenv](#vlucasphpdotenv)
        - [Illuminate/Database](#illuminatedatabase)
        - [Slim 4](#slim-4)
        - [Phinx](#phinx)
        - [Respect/Validation](#respectvalidation)
    - [Frontend packages](#frontend-packages)
        - [Gulp](#gulp)
- [Packaged classes and interfaces](#packaged-classes-and-interfaces)
    - [Views](#views)
    - [Sessions](#sessions)
    - [Validation](#validation)

# Installation
The installation process is very simple. First you need to make sure that you have composer installed, and that you're running PHP 7.4.0 or greater.

To install all backend packages:
```
$ composer install
```

To start the dev server:
```
$ cd public/
$ php -S 127.0.0.1:8080
```

This boilerplate also comes with a frontend setup. This setup will allow you to use Sass (.scss files).
To install all frontend packages:
```
$ npm install
```

# Installed packages and dependencies
This boilerplate app setup comes with several backend packages and technologies that hopefully will give your future application a bit more of structure, and makes it overall easier to develop it.

## Backend packages
### Vlucas/PHPDotenv
This package enables your application to load variables from a .env file. Upon creating a .env in the root of your project (the same folder as this readme.md file is located in) the variables within that file will be loaded into your application.

[Read more about this package.](https://github.com/vlucas/phpdotenv)

### Illuminate/Database
This package enables your application to make use of the models and query builder of the Laravel framework.

Documentation can be found at the offical Laravel website.

* [Models](https://laravel.com/docs/7.x/eloquent)
* [Query builder](https://laravel.com/docs/7.x/queries)

[Read more about this package](https://github.com/illuminate/database)

### Slim 4
This framework enables your application to makes use of:
* Routing
* Controllers
* Middleware
* Dependency container

[Read more about Slim 4](http://www.slimframework.com/docs/v4/)

### Phinx
This package enables you to make use of database migrations and seeders.

[Read more about Phinx](https://phinx.org/)

### Respect/Validation
This library makes validation a breeze.

[Read more about Respect/Validation](https://github.com/Respect/Validation)

## Frontend packages

### Gulp
Gulp is a task runner that will take Sass (.scss) files and compile them into one minified file (app.min.css)

Gulp uses the following packages:
* gulp-rename
* gulp-sass
* node-sass

# Packaged classes and interfaces
This boilerplate has some pre-written classes and interfaces that will hopefully make developing your application a bit easier.

## Views
**Namespace: App/Core/Views**

Views are very important in a MVC application, because of this the boilerplate comes with 2 view classes.
* View (App\Core\Views\View)
* TwigView (App\Core\Views\TwigView)

The view classes implement the App\Interfaces\Views\ViewInterface interface. It is possible to create a new view class with a different template engine. All you have to do is create a new view class and implement the ViewInterface.

When passed the controller's request and dependency container, your view file will have access to the following variables:
* baseURL (The base of the server, e.g: https://example.com)
* csrf (Some html markup that contains the necessary fields for a CSRF protected form request)

**Important:** Views have their own base directories. This makes your application a bit more clean and structured since all views will be seperated based off of their type (PHP or Twig). Because of these base directories, any attempt of loading views outside of them is not encouraged (and impossible for twig templates).

**View**<br/>
This is the standard view class. It uses standard PHP syntax and produces a string of HTML markup (your view content).

Creating a new view is pretty simple. First you need to create a new view file in *./resources/views*. Within this folder you can create your view file that ends with the `.php` file extension.
<br/>
The markup of a standard PHP view looks like this.
```html
<!-- File: ./resources/views/php/Helloworld.php -->
<h1>Hello <?= $slug ?>!</h1>
```
In the backend you then need to load and render your view in your controller. That can be done like this:
```php
<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controllers\BaseController;
use App\Core\Views\View;

class HelloWorldController extends BaseController
{
    public function __invoke(Request $request, Response $response)
    {
        $output = "";

        // Create the view file that is located in ./resources/views/php/helloworld.php
        $view = new View('php/helloworld');

        // Pass the request and dependency container to the view
        $view->setRequest($request);
        $view->setContainer($this->container);

        // Pass a variable to the view and load it
        $view->withVariables(['slug' => "World"]);
        $view->load();

        // Render the view
        $output = $view->render();

        // Return a response from the controller
        $response->getBody()->write($output);
        return $response;
    }
}
```

**TwigView**<br/>
This is the alternative view class. It uses the [twig templating engine](https://twig.symfony.com/) and produces a string of HTML markup (your view content).

Creating a new view is pretty simple. First you need to create a new view file in *./resources/views*. Within this folder you can create your view file that ends with the `.twig` file extension.
<br/>
The markup of a standard Twig view looks like this.
```twig
{# File: ./resources/views/helloworld.twig #}

{% extends "layouts/base.twig" %}

{% block css %}
<link rel="stylesheet" href="{{ baseURL }}/css/app.min.css" type="text/css">
{% endblock %}

{% block content %}
<div class="container-welcome">
    <h1>Hello {{ slug }}!</h1>
</div>
{% endblock %}
```
As you can see, this file is structured a bit differently. Twig lets a developer use base layouts and blocks to construct a more complex page. As you can see, a CSS stylesheet is loaded right above the content. This is very handy since we can load page-specific stylesheet much easier now.

You can read more about the twig template syntax [here](https://twig.symfony.com/doc/3.x/)

In the backend you then need to load and render your view in your controller. That can be done like this:
```php
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

        // Create the view file that is located in ./resources/views/twig/helloworld.twig
        $view = new TwigView('helloworld');

        // Pass the request and dependency container to the view
        $view->setRequest($request);
        $view->setContainer($this->container);

        // Pass a variable to the view and load it
        $view->withVariables(['slug' => "World"]);
        $view->load();

        // Render the view
        $output = $view->render();

        // Return a response from the controller
        $response->getBody()->write($output);
        return $response;
    }
}
```
As you can see, the code is almost identical to the standard PHP view code. The reason for this is because we use the ViewInterface interface to set a standard syntax for view classes.

## Sessions
**Namespace: App\Core\Sessions**

It is very likely that your future application will be making use of sessions. Because of this, this boilerplate will come with the following classes:
* Session (App\Core\Sessions\Session)
* FlashMessage (App\Core\Sessions\FlashMessage)

These session classes do not have any standard interfaces to implement since a session class can have too many differences for this.

**Session**<br/>
This class can be used for simple sessions with a `key => value` structure.

The basics of using the session class are as follow:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Creates a new session object
$session = new Session('test');

// Put some data into the session
$session->setKey('keyOne', "123");
$session->setKey('keyTwo', "456");

// Actually create the session
$session->create();
```
The code above creates a new session. When the create method is called all of the options get evaluated and a new $_SESSION variable is registered. The session will not be created if a different session with the same name already exists.

You can retrieve an existing session as follows:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Will try to retrieve a session with the name 'test'
$session = Session::getExisting('test');
```
This will look into the $_SESSION superglobal and search for any session with the same name. It then constructs a Session object and returns it to you.

You can update a session as follows:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Will try to retrieve a session with the name 'test'
$session = Session::getExisting('test');

// Update the 'keyOne' key
$session->setKey('keyOne', ['arrayception' => '123']);

// Save the changes
$session->update();
```

You can destroy a session as follows:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Will try to retrieve a session with the name 'test'
$session = Session::getExisting('test');

// Destroys the session
$session->destroy();
```

Up until now, we've only taken a look at sessions that will exist until the destroy() method is called. But what if you want to create a logged in session?

You can set the timeout of a session as follows:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Creates a new session object
$session = new Session('test');

// Put some data into the session
$session->setKey('keyOne', '123');

// Set the timeout IN SECONDS
$session->setTimeout(5);

// Actually create the session
$session->create();
```

This session will exist until you refresh it, or until you request it again:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Refreshes the session without having to request it
Session::refreshSessionTimeout('test');

// Refreshes the session when you request it
$session = Session::getExisting('test');
```
If you intend to use the session class outside of this project (which is 100% allowed), you need to call the following method to actually destroy a timed out session without requesting it:
```php
<?php
namespace App;

use App\Core\Sessions\Session;

// Removes all timed out sessions
Session::removeAllTimedOutSessions();
```

**FlashMessage**<br/>
This class can be used to create flash messages.

A new flash message can be created as follows:
```php
<?php
namespace App;

use App\Core\Sessions\FlashMessage;

$flashMessage = new FlashMessage('test');

$flashMessage->setKey('keyOne', '123');
$flashMessage->create();
```
As you can see, a flash message is very similar to the Session object. The only real difference is that you can't destroy the flash message, and can't update it. The message will only be alive for a total of 1 refresh.

A flash message can be retrieved as follows:
```php
<?php
namespace App;

use App\Core\Sessions\FlashMessage;

$flashMessage = FlashMessage::getExisting('test');
```

If you intend to use the session class outside of this project (which is 100% allowed), you need to call the following method to actually destroy the flash messages:
```php
<?php
namespace App;

use App\Core\Sessions\FlashMessage;

FlashMessage::destroyAllOldMessages();
```

## Validation
**namespace: App\Core\Validation**  

Any application needs to do validation of user data. This boilerplate comes packaged with a simple user input validation class that is basically a wrapper for the Respect/Validation library.

**RespectValidator**
Before actually using this wrapper it would be a wise idea that you get some experience with using respect/validation normally.

The basic validation sequence is as follows:
```php
<?php
namespace App;

use Respect\Validation\Validator as v;
use App\Core\Validation\RespectValidator;

$validator = new RespectValidator($request);
$validator->setRules([
    'post_value_one' => v::stringType()->noWhitespace(),
    'post_value_two' => v::stringType()->email()
]);

$validator->validate();
if(!$validator->isValid())
{
    // Do error handling.
    $errorMessages = $validator->getErrorMessages();
}
else
{
    // Do some other stuff.
}
```

As you can see, validating POST request data is pretty easy. The only thing that you need to do is `use use Respect\Validation\Validator as v` to actually access the rules. After that you can use the validator by setting some rules. The key of a rule refers to the $_POST value of the request. The value is a respect/validation sequence.

After setting the rules you can validate the data and check if the validation was valid. If the validation wasn't valid, you can use the `getErrorMessages()` method to get the error messages.