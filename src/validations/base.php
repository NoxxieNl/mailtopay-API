<?php
namespace bosveld\mailtopay\validations;

class base {

    CONST REQUIRED = true;
    CONST OPTIONAL = false;
    
    protected $error;

    public function getError()
    {
        return $this->error;
    }

    protected function setError($error)
    {
        $this->error = $error;
    }
}