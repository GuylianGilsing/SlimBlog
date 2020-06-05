<?php
namespace App;

use App\Core\Sessions\Session;
use App\Core\Sessions\FlashMessage;

require_once __DIR__.'/../resources/app/functions.php';

StartSession();

require_once __DIR__.'/phpdotenv/environment.php';
require_once __DIR__.'/eloquent/database.php';
require_once __DIR__.'/slim/app.php';

// Make sure to destroy all old flash messages and timed out sessions.
Session::removeAllTimedOutSessions();
FlashMessage::destroyAllOldMessages();