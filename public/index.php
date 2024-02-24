<?php

require_once '../vendor/autoload.php';
require_once 'assets/functions.php';
use App\Models\Router;


$router = new Router();
$router->route();

session_start();
