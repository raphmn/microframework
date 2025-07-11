<?php

namespace App;

use AltoRouter;

class Routeur
{
    public static function route()
    {
        $router = new AltoRouter();

        // Définition des routes
        self::set_routes($router);

        // Matcher la route
        $match = $router->match();

        if ($match) {
            list($controllerName, $method) = explode('#', $match['target']);
            $fqcn = 'App\\Controller\\' . $controllerName;

            if (class_exists($fqcn)) {
                $controller = new $fqcn();

                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $match['params']);
                    return;
                } else {
                    http_response_code(500);
                    require "View/Layouts/Errors/501.html";
                    echo "Méthode '$method' inexistante dans $fqcn.";
                    return;
                }
            } else {
                http_response_code(500);
                require "View/Layouts/Errors/500.html";
                echo "Contrôleur '$fqcn' introuvable.";
                return;
            }
        } else {
            http_response_code(404);
            require "View/Layouts/Errors/404.html";
        }
    }

    private static function set_routes($router)
    {
        $routes = require "routes.php";
        foreach ($routes as $route)
        {
            call_user_func_array([$router, 'map'], $route);
        }
    }
}
