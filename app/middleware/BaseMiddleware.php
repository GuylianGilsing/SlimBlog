<?php
namespace App\Middleware;

use DI\Container;

class BaseMiddleware
{
    protected ?Container $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}