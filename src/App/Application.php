<?php

namespace AvaiBook\App;


use Exception;

class Application {

    public function __construct() {
        try {

            $router = new Router();

            if ( !class_exists( $router->controller['absPath'] ) ) {
                throw new AppException("Controlador no definido. ( {$router->controller['name']} )");
            }

            if ( !method_exists( $router->controller['absPath'], $router->method )) {
                throw new AppException("Método del controlador no definido. ( {$router->controller['name']}->$router->method )");
            }

            $object = new $router->controller['absPath']( $router->params );
            $object->{$router->method}();

            $this->render( $object->getResponse() );

        } catch ( Exception $exception ){
            error_log($exception->getMessage());
            $response = new Response();
            $response->setCode(400)->setError( $exception->getMessage() );
            $this->render($response);
        }
    }

    private function render( Response $response ) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code( $response->getCode() );

        if( $response->getCode() < 400 ) {
            if( $response->getCode() != 204 ) {
                $msg = empty( $response->getError() ) ? $response->getData() : [ 'error' => $response->getError() ];
                echo json_encode( $msg );
            }
        } else {
            echo json_encode( [ 'error' => $response->getError() ] );
        }
    }

}

