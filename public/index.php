<?php

require_once '../vendor/autoload.php';

use App\Models\Router;


$router = new Router();
$router->route();

session_start();
