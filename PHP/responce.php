<?php

class Responce {
    private $statusCode = 200;
    private $data = null;
    private $error = null;

    public static function withError($statusCode, $error)
    {
        $instance = new self();
        $instance->$statusCode = $statusCode;
        $instance->$error = $error;
    }

    public static function withData($data)
    {
        $instance = new self();
        $instance->$data = $data;
    }

    function setStatusCode($statusCode){
        $this->$statusCode = $statusCode;
    }

    function getStatusCode(){
        return $this->statusCode;
    }

    function setData($data){
        $this->$data = $data;
    }
    
    function getData(){
        return $this->data;
    }

    function setError($error){
        $this->error = $error;
    }
    
    function getError(){
        return $this->error;
    }
}