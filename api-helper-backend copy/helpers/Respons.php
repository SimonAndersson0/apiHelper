<?php
// helpers/Response.php
// Standardized verbose JSON responses (Format B)

class Response {
    public static function send($status, $message = "", $data = [], $startTime = null) {
        if ($startTime !== null) {
            $ms = round((microtime(true) - $startTime) * 1000);
            $exec = $ms . "ms";
        } else {
            $exec = null;
        }

        $payload = [
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ];

        if ($exec !== null) $payload["exec_time"] = $exec;

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success($message = "OK", $data = [], $startTime = null) {
        self::send("success", $message, $data, $startTime);
    }

    public static function error($message = "Error", $data = [], $startTime = null, $httpCode = 400) {
        http_response_code($httpCode);
        self::send("error", $message, $data, $startTime);
    }
}
