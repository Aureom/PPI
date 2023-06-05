<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/interfaces/errors/Base.php');

class BadRequest extends BaseError {
    public $message;
    public $statusCode;

    public function __construct($message) {
        header("HTTP/1.1 400 Bad Request");

        $this->message = $message;
        $this->statusCode = 400;
    }
}

?>