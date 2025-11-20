<?php
require_once __DIR__ . '/../../classes/project-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new ProjectHandler();
$id = $data['id'] ?? null;

if (!$id) Response::error("Project ID is required");

$handler->getProject($id);
