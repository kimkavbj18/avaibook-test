<?php

namespace AvaiBook\Controllers;


use AvaiBook\App\Response;

class Controllers {

    protected array $get;
    protected array $post;
    protected array $put;
    protected Response $response;

    /**
     * @param array $params
     */
    final public function __construct( array $params = [] ) {
        $this->get = array_filter( $params );

        $this->post = $_POST;
        $_POST = null;
        if( empty( $this->post ) ) {
            $this->post = json_decode( file_get_contents( 'php://input' ),true ) ?? [];
            if ( empty( $this->post ) )  {
                parse_str( file_get_contents( "php://input" ), $this->post );
            }
        }

        $this->put = json_decode( file_get_contents( 'php://input' ), true ) ?? [];
        if( empty( $this->put ) ) {
            parse_str( file_get_contents( "php://input" ), $this->put );
        }

        $this->response = new Response();
    }

    /**
     * Método GET por default si no viene un método definido en la ruta
     */
    final function get() {
        $this->response->setCode('201')->setData(['msg' => 'Ejecutando método GET por default del controlador controllers.php']);
    }

    /**
     * Método POST por default si no viene un método definido en la ruta
     */
    final function post() {
        $this->response->setCode('201')->setData(['msg' => 'Ejecutando método POST por default del controlador controllers.php']);
    }

    /**
     * Método PUT por default si no viene un método definido en la ruta
     */
    final function put() {
        $this->response->setCode('201')->setData(['msg' => 'Ejecutando método PUT por default del controlador controllers.php']);
    }

    /**
     * Método getter que retorna el objeto Response
     * @return Response
     */
    final public function getResponse() : Response {
        return $this->response;
    }

}
