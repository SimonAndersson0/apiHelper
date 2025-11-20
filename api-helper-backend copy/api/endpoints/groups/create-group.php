<?php
require_once __DIR__ . '/../../classes/GroupHandler.php';
require_once __DIR__ . '/../../helpers/Response.php';

$handler = new GroupHandler();

$title = $data['title'] ?? null;
$projectId = $data['project_id'] ?? null;

if (!$title || !$projectId) {
    Response::error('Missing required parameters: title or project_id');
}

echo $handler->createGroup($title, $projectId);
