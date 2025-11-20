<?php
require_once __DIR__ . '/../config/db.php';

class BaseHandler {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->conn();
    }

    public function __destruct() {
        $this->db = null;
    }
}
?>
