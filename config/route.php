<?php
use FastRoute\RouteCollector;

$routes = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', 'HomeController@index');
    //AUTHENTIFICATION/INSCRIPTION
    $r->addRoute('GET', '/loginpage', 'LoginController@index');
    $r->addRoute('POST', '/inscription', 'LoginController@inscription');
    $r->addRoute('GET', '/deconnexion', 'LoginController@deconnexion');
    $r->addRoute('POST', '/connexion', 'LoginController@connexion');
    //LOGEMENT
    $r->addRoute('POST', '/search_lodgement', 'HomeController@recherche');
    $r->addRoute('GET', '/logement/{id:\d+}', 'LodgementController@index');
    //MON COMPTE
    $r->addRoute('GET', '/moncompte', 'AccountController@index');
    $r->addGroup('/moncompte', function(RouteCollector $r) {
        $r->addRoute('GET', '/logement/ajouter', 'AccountController@ajouter_logement');
        $r->addRoute('GET', '/logement/modifier/{id:\d+}', 'AccountController@modifier_logement');
        $r->addRoute('POST', '/change_password', 'AccountController@change_password');
    });
    //BACKOFFICE
    $r->addRoute('GET', '/backoffice', 'BackOfficeController@index');
    $r->addRoute('POST', '/connexionback', 'BackOfficeController@connexion');
    $r->addGroup('/backoffice', function(RouteCollector $r) {
        $r->addRoute('GET', '/dashboard', 'BackOfficeController@dashboard');
        $r->addRoute('GET', '/deconnexion', 'BackOfficeController@deconnexion');
        $r->addRoute('GET', '/utilisateurs', 'Backoffice\UtilisateursController@listes');
        $r->addRoute('GET', '/utilisateur', 'Backoffice\UtilisateursController@ajouter');
        $r->addRoute('GET', '/utilisateur/{id:\d+}', 'Backoffice\UtilisateursController@voir');
        $r->addRoute('GET', '/logements', 'Backoffice\LodgementsController@listes');
        $r->addRoute('GET', '/logement', 'Backoffice\LodgementsController@ajouter');
        $r->addRoute('GET', '/logement/{id:\d+}', 'Backoffice\LodgementsController@voir');
    });
    $r->addRoute('POST', '/search_user', 'Backoffice\UtilisateursController@recherche');
    $r->addRoute('POST', '/save_user', 'Backoffice\UtilisateursController@save');
    $r->addRoute('POST', '/delete_user', 'Backoffice\UtilisateursController@delete');

    $r->addRoute('POST', '/save_lodgement', 'LodgementController@save');
    $r->addRoute('POST', '/delete_lodgement', 'LodgementController@delete');
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $routes->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/404.php';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // handle 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        list($controller_name, $method_name) = explode('@', $routeInfo[1]);
        $controller_class = "App\\Controllers\\".$controller_name;
        $controller_object = new $controller_class();
        if (isset($routeInfo[2]) && is_array($routeInfo[2])) {
            $params = $routeInfo[2];
            $controller_object->$method_name(...$params);
        } else {
            $controller_object->$method_name();
        }
        break;
}