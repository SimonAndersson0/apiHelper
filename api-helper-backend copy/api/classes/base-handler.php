<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

class BaseHandler {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->conn();
    }
}
