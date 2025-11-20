<?php
require_once __DIR__ . '/../../classes/project-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new ProjectHandler();
$name = $data['name'] ?? null;
$desc = $data['description'] ?? null;

if (!$name) Response::error("Project name is required");

$handler->createProject($name, $desc);
