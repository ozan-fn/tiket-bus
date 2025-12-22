<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define("LARAVEL_START", microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . "/../storage/framework/maintenance.php")) {
    require $maintenance;
}

// Check if storage folder exists and create link if needed
if (file_exists(__DIR__ . "/../storage")) {
    $target = __DIR__ . "/../storage/app/public";
    $link = __DIR__ . "/storage";
    if (!file_exists($link) && file_exists($target)) {
        symlink($target, $link);
    }
}

// Register the Composer autoloader...
require __DIR__ . "/../vendor/autoload.php";

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . "/../bootstrap/app.php";

$app->handleRequest(Request::capture());
