<?php

require_once __DIR__ . "/BaseError.php";

class BadRequest extends BaseError {

    public function __construct($message) {
        parent::__construct($message, 400);
    }
}

?>