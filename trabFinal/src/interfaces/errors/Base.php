<?php

class BaseError {
    public $message;
    public $statusCode;

    public function __construct($message, $statusCode) {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
}

?>