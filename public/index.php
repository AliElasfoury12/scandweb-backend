<?php
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

use app\controllers\GraphQLController;
use app\core\App;

require_once __DIR__."/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

try { 
    $app = new App(dirname(__DIR__));
} catch (\Throwable $th) {
   App::dd([$th]);
}

$app->router->post('/', [GraphQLController::class, 'handle']);
$app->router->get('/', function () {
    return "ali";
});

try { 
    $app->run();
} catch (\Throwable $th) {
   App::dd([$th]);
}
