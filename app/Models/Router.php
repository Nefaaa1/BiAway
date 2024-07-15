<?php

namespace App\Models;

class Router {
    private $routes = array(
        '/' => 'HomeController@index',
        '/loginpage' => 'LoginController@index',
        '/logement/([0-9]+)' => 'LodgementController@index',
        '/inscription' => 'LoginController@inscription',
        '/connexion' => 'LoginController@connexion',
        '/deconnexion' => 'LoginController@deconnexion',
        '/backoffice' => 'BackOfficeController@index',
        '/connexionback'=> 'BackOfficeController@connexion',
        '/backoffice/dashboard' => 'BackOfficeController@dashboard',
        '/backoffice/deconnexion' => 'BackOfficeController@deconnexion',
        '/backoffice/utilisateurs' => 'Backoffice\UtilisateursController@listes', // Utilisateur debut
        '/backoffice/utilisateur' => 'Backoffice\UtilisateursController@ajouter',
        '/search_user' => 'Backoffice\UtilisateursController@recherche',
        '/save_user' => 'Backoffice\UtilisateursController@save',
        '/delete_user' => 'Backoffice\UtilisateursController@delete',
        '/backoffice/utilisateur/([0-9]+)' => 'Backoffice\UtilisateursController@voir', // Utilisateur fin
        '/backoffice/logements' => 'Backoffice\LodgementsController@listes', // Logement debut
        '/backoffice/logement' => 'Backoffice\LodgementsController@ajouter',
        '/search_lodgement' => 'HomeController@recherche',
        '/save_lodgement' => 'Backoffice\LodgementsController@save',
        '/delete_lodgement' => 'Backoffice\LodgementsController@delete',
        '/backoffice/logement/([0-9]+)' => 'Backoffice\LodgementsController@voir', // Logement fin
    );

    public function route() {
        $request = $_SERVER['REQUEST_URI'];
        foreach ($this->routes as $pattern => $controllerAction) {
            if (preg_match('#^' . $pattern . '$#', $request, $matches)) {
                array_shift($matches); // Supprime le premier élément du tableau, qui contient la chaîne complète correspondant au motif
                $controllerAction .= '@' . implode(',', $matches); // Ajoute les paramètres d'URL à la chaîne d'action du contrôleur
                $this->controllerAction($controllerAction);
                return;
            }
        }
        require_once $_SERVER['DOCUMENT_ROOT']. '/app/Views/404.php';
    }

    private function controllerAction($controllerAction) {
        list($controllerName, $actionName, $parameters) = explode('@', $controllerAction);
        $controllerClassName = 'App\\Controllers\\' . $controllerName;
        $controllerInstance = new $controllerClassName();
        call_user_func_array(array($controllerInstance, $actionName), explode(',', $parameters));
    }
}
