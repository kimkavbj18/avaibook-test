<?php

namespace AvaiBook\App;


use AvaiBook\App\Common\InitUtils;

class Router {

    private string $url;
    public array $controller;
    public string $method;
    public array $params;
    private string $httpMethod;

    final public function __construct() {

        $this->url = $this->getURL();
        $this->httpMethod = $this->getHTTPMethod();
        $this->params = $this->getParams();

        # Opción #1: Usar un archivo .INI
        $routes = ( new InitUtils() )->getIniVars('routes.ini')['routes'];

        # Opción #2: Usar un archivo .PHP
        //$routes = require_once BASE_PATH . '/routes.php';

        list( $controller, $method ) = explode( '@', $this->findObject( $routes ) );

        $this->controller = [
            'name' => $controller,
            'absPath' => "\\AvaiBook\\Controllers\\$controller"
        ];

        $this->method = $method ?? $this->httpMethod;

    }

    private function getURL() : string {
        return filter_var( rtrim( $_SERVER['REQUEST_URI'], '/' ), FILTER_SANITIZE_STRING );
    }

    private function getHTTPMethod() : string {
        return strtoupper( $_SERVER['REQUEST_METHOD'] );
    }

    private function findObject( $routes ) : string {

        $object = '';

        foreach( $routes as $method_to_check => $raw_route) {
            list( $pattern_to_check, $object_to_load ) = explode(':', $raw_route );

            if ( strtoupper($method_to_check) == $this->httpMethod && preg_match( '/^'.$pattern_to_check.'$/', $this->url ) ) {
                $object = $object_to_load;
                break;
            }
        }

        return  $object;

    }

    private function getParams() : array {
        $uri = explode('/', $this->url);
        array_shift( $uri );

        $_uri = [];

        for($i=0; $i < count($uri); $i = $i + 2){
            $_uri[ $uri[$i] ] = $uri[ $i+1 ] ?: null;
        }

        return $_uri;
    }

}

