<?php

/**
 * Returns the server base e.g: http://example.com/
 * @return string Returns the HTTP protocol + domain name.
 */
function ServerBase()
{
    $protocol = 'http://';

    if(isset($_SERVER['HTTPS']) == true)
        $protocol = 'https://';

    $base = $_SERVER['HTTP_HOST'];

    return $protocol.$base;
}

/**
 * Only starts a session when no sessions are started.
 */
function StartSession()
{
    $sessionStatus = session_status();
    if($sessionStatus == PHP_SESSION_DISABLED)
    {
        echo '<h1>Sessions must be enabled.</h1>';
        die();
    }
    else if($sessionStatus == PHP_SESSION_NONE)
    {
        session_start();
    }
}