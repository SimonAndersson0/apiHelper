// endpoints/projects.php

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function projects($data) {
    global $pdo;

    // Example: list all projects
    $stmt = $pdo->query("SELECT * FROM projects");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($projects);
}
?>

// endpoints/groups.php

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function groups($data) {
    global $pdo;

    // Optional filter by project_id
    $sql = "SELECT * FROM groups";
    $params = [];
    if (isset($data['project_id'])) {
        $sql .= " WHERE project_id = :project_id";
        $params[':project_id'] = $data['project_id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($groups);
}
?>

// endpoints/endpoints.php

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function endpoints($data) {
    global $pdo;

    $sql = "SELECT * FROM endpoints";
    $params = [];
    if (isset($data['group_id'])) {
        $sql .= " WHERE group_id = :group_id";
        $params[':group_id'] = $data['group_id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($results);
}
?>

// endpoints/variations.php

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function variations($data) {
    global $pdo;

    $sql = "SELECT * FROM variations";
    $params = [];
    if (isset($data['endpoint_id'])) {
        $sql .= " WHERE endpoint_id = :endpoint_id";
        $params[':endpoint_id'] = $data['endpoint_id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($results);
}
?>

// endpoints/parameters.php

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function parameters($data) {
    global $pdo;

    $sql = "SELECT * FROM parameters";
    $params = [];
    if (isset($data['variation_id'])) {
        $sql .= " WHERE variation_id = :variation_id";
        $params[':variation_id'] = $data['variation_id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($results);
}
?>
