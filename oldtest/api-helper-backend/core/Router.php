<?php

class Router {

    public static function route($method, $path, $callback) {
        if ($_SERVER['REQUEST_METHOD'] !== $method) return;

        if ($_SERVER['REQUEST_URI'] === $path || strstr($_SERVER['REQUEST_URI'], $path)) {
            $callback();
            exit;
        }
    }
}
