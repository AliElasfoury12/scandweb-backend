<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST");

use app\controllers\GraphQLController;
use app\controllers\SiteController;
use app\core\App;
use app\core\Router;

require_once __DIR__."./../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

try { 
    $app = new App(dirname(__DIR__));
} catch (\Throwable $th) {
   App::dd([$th]);
}

Router::post('/', [GraphQLController::class, 'handle']);

try { 
    $app->run();
} catch (\Throwable $th) {
   App::dd([$th]);
}
