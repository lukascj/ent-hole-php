<?php
// namespace EntHole;

class Router {
    protected $_routes = [];
    
    private function add_route($route, $controller, $action, $method) {
        $this->_routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action) {
        $this->add_route($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action) {
        $this->add_route($route, $controller, $action, "POST");
    }

    public function dispatch() {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method =  $_SERVER['REQUEST_METHOD'];

        if(array_key_exists($uri, $this->_routes[$method])) {
            $controller = $this->_routes[$method][$uri]['controller'];
            $action = $this->_routes[$method][$uri]['action'];

            $controller = new $controller();
            $controller->$action();
        } else {
            throw new \Exception("No route found for URI: $uri");
        }
    }
}