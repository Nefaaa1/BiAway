<?php
// Configure PHP
setlocale(LC_ALL, array('fr_FR.UTF-8', 'fr_FR', 'fr'));
ini_set('date.timezone', 'Europe/Paris');
header_remove('X-Powered-By');
ini_set('display_errors', 1);
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self';  img-src 'self' data:; font-src 'self';  connect-src 'self'; frame-ancestors 'none'; form-action 'self';  base-uri 'self';");

session_start();

require_once './vendor/autoload.php';
require_once './public/assets/functions.php';

// use App\Models\Router;

// $router = new Router();
// $router->route();



//Charger les routes
require_once './config/route.php';
