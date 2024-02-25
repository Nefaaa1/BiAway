<?php

// Configure PHP
setlocale(LC_ALL, array('fr_FR.UTF-8', 'fr_FR', 'fr'));
ini_set('date.timezone', 'Europe/Paris');
header_remove('X-Powered-By');
ini_set('display_errors', 1);

session_start();
require_once '../vendor/autoload.php';
require_once 'assets/functions.php';
use App\Models\Router;


$router = new Router();
$router->route();


