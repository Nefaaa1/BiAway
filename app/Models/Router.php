<?php

namespace App\Models;

class Router {
    private $routes = array(
        '/' => 'HomeController@index',
        '/loginpage' => 'LoginController@index'
    );

    public function route() {
        $request = $_SERVER['REQUEST_URI'];
        

        if (array_key_exists($request, $this->routes)) {
            $controllerAction = $this->routes[$request];
            $this->controllerAction($controllerAction);
        } else {
            echo "404 Not Found";
        }
    }

    private function controllerAction($controllerAction) {
        list($controllerName, $actionName) = explode('@', $controllerAction);
        $controllerClassName = 'App\\Controllers\\' . $controllerName;


        $controllerInstance = new $controllerClassName(); 
        $controllerInstance->$actionName();
    }
}
