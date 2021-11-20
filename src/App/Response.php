<?php

namespace AvaiBook\App;


class Response {

    protected int $code = 200;

    protected array $data = [];

    protected string $error = '';

    /**
     * @param int $code
     * @return $this
     */
    public function setCode( int $code ) : Response {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode() : int {
        return $this->code;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData( $data ) : Response {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError( string $error ) : Response {
        $this->error = $error;
        return $this;
    }

    /**
     * @return string
     */
    public function getError() : string {
        return $this->error;
    }

}
