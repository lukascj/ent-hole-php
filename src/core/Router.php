<?php

class Router {
    protected $_routes = [];
    
    private function add_route($route, $method, $action) {
        $this->_routes[$method][$route] = ['action' => $action];
    }

    public function get($route, $action) {
        $this->add_route($route, "GET", $action);
    }

    public function post($route, $action) {
        $this->add_route($route, "POST", $action);
    }

    public function dispatch($request) {
        if(!array_key_exists($request['path'], $this->_routes[$request['method']])) {
            throw new Exception("No route found for URI: $request[path]");
        }
        $func = $this->_routes[$request['method']][$request['path']]['action'];
        call_user_func($func, $request);
    }
}