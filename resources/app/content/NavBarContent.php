<?php
namespace App\Content;

use App\Core\Sessions\Session;

class NavBarContent
{
    private static $items = [];
    
    public static function getNavbarItems()
    {
        self::$items['/'] = "Blogs";

        $userSession = Session::getExisting('user');

        if($userSession === null)
        {
            self::$items['/login'] = "Login";
            self::$items['/register'] = "Register";
        }
        else
        {
            self::$items['/dashboard'] = "Dashboard";
            self::$items['/logout'] = "Logout";
        }

        return self::$items;
    }
}