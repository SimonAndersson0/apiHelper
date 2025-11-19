# apiHelper
hopefully make an helper that can easily test and create documentation somewhat dynamicly

---


# API Documentation (`API_DOCUMENTATION.md`)

## Base URL

```
http://your-domain.com/index.php?endpoint=ENDPOINT_NAME
```

## Request Method

* Most endpoints accept **POST** for actions that modify data.
* GET for retrieving information.

## How to call an endpoint

Example: `create_user`

### Request

```
POST /index.php?endpoint=create_user
Content-Type: application/x-www-form-urlencoded

username=testuser
password=123456
```

### Response

```json
{
  "status": "success",
  "message": "User created"
}
```

## Endpoint structure

* **File name**: `endpoints/ENDPOINT_NAME.php`
* **Function name**: `ENDPOINT_NAME($data)`

### Example endpoint file

```php
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/Response.php';

function create_user($data) {
    global $pdo;

    if (!isset($data['username']) || !isset($data['password'])) {
        Response::error('Missing required fields');
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute([
        ':username' => $data['username'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
    ]);

    Response::success('User created');
}
?>
```

## Notes

* The `$data` parameter contains all POST or GET parameters.
* You can add new endpoints by creating a file in `endpoints/` and writing a function with the same name as the file.
* Responses should always use the `Response` helper for consistency.

---

This setup makes your API modular and frontend-friendly.

I can also **add a few example endpoints (`projects`, `groups`, `endpoints`) in the same style** so your frontend team has something to start testing with.

Do you want me to do that next?



more info in example-endpoints.php