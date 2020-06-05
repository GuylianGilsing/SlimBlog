<?php
namespace App;

use Dotenv\Dotenv;

// Makes all variables within the .env file (located within the root dir)
// availlable to use within the application.
$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

// Define your own environment variables here...
// $_ENV['test'] = "myvalue";

// Define your own constants here...
define('VIEW_DIR', __DIR__.'/../../resources/views/');