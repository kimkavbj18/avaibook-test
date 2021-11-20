<?php

namespace AvaiBook\App;


class Response {

    protected int $code = 200;

    protected array $data = [];

    protected string $error = '';

    public function setCode( int $code ) : Response {
        $this->code = $code;
        return $this;
    }

    public function getCode() : int {
        return $this->code;
    }

    public function setData( $data ) : Response {
        $this->data = $data;
        return $this;
    }

    public function getData() {
        return $this->data;
    }

    public function setError( string $error ) : Response {
        $this->error = $error;
        return $this;
    }

    public function getError() : string {
        return $this->error;
    }

}
