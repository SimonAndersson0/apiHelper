<?php

require_once __DIR__ . "/Response.php";
require_once __DIR__ . "/../config/database.php";

class Controller {

    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->conn();
    }

    protected function input() {
        return json_decode(file_get_contents("php://input"), true) ?? [];
    }
}
