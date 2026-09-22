<?php

require_once "vendor/autoload.php";

use Model\Boletim;
use Controller\BoletimController;

$caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$partes = explode("/", $caminho);

$recurso = $partes[2] ?? null;
$id = $partes[3] ?? null;

header("Content-Type: application/json; charset=UTF-8");

if ($recurso !== "medias") {
    http_response_code(404);
    echo json_encode(["error" => "Rota desconhecida!"]);
    exit;
}

try {
    $controller = new BoletimController(new Boletim());
    $controller->tratarRequisicao($_SERVER['REQUEST_METHOD'], $id);
} catch (\Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erro interno."]);
}
