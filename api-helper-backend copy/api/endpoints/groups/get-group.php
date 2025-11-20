<?php
require_once __DIR__ . '/../../classes/GroupHandler.php';
require_once __DIR__ . '/../../helpers/Response.php';

$handler = new GroupHandler();

$id = $data['id'] ?? null;

if (!$id) {
    Response::error('Group ID is required');
}

echo $handler->getGroup($id);
