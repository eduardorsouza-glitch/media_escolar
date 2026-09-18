```php
<?php

require_once __DIR__ . '/Controller/BoletimController.php';

$controller = new BoletimController();

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', trim($uri, '/'));

$id = $uri[1] ?? null;

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $resultado = $id
            ? $controller->show($id)
            : $controller->index();
        break;

    case 'POST':
        $resultado = $controller->store();
        break;

    case 'PUT':
        $resultado = $controller->update($id);
        break;

    case 'DELETE':
        $resultado = $controller->delete($id);
        break;

    default:
        $resultado = ['erro' => 'Método não permitido'];
        break;
}

echo json_encode($resultado);
```
