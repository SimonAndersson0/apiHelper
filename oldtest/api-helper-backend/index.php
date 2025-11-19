<?php

header("Content-Type: application/json");

require_once __DIR__ . "/core/Router.php";

// Import endpoint modules
require_once __DIR__ . "/endpoints/projects.php";
require_once __DIR__ . "/endpoints/groups.php";
require_once __DIR__ . "/endpoints/endpoints.php";
require_once __DIR__ . "/endpoints/variations.php";
require_once __DIR__ . "/endpoints/parameters.php";

echo json_encode([
    "status" => "error",
    "message" => "Invalid route"
]);
