<?php

class BaseError {
    private string $message;
    private int $code;

    public function __construct($message, $code) {
        http_response_code($code);
        $this->message = $message;
        $this->code = $code;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getCode() {
        return $this->code;
    }

    public function toJson(): false|string {
        header('Content-Type: application/json');
        return json_encode([
            'message' => $this->message,
            'code' => $this->code
        ], JSON_THROW_ON_ERROR);
    }
}

?>