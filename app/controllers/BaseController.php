<?php
namespace App\Controllers;

use DI\Container;

class BaseController
{
    protected ?Container $container = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}