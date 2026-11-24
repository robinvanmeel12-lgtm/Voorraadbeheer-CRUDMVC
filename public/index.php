<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/models/Item.php';
require __DIR__ . '/../app/controllers/ItemController.php';

$routes = [
    'overzicht' => ['GET' => 'index'],
    'toevoegen' => ['GET' => 'create', 'POST' => 'store'],
];

$action = $_GET['actie'] ?? 'overzicht';
$method = $_SERVER['REQUEST_METHOD'];

if (!is_string($action) || !isset($routes[$action])) {
    http_response_code(404);
    exit('Deze pagina bestaat niet.');
}

if (!isset($routes[$action][$method])) {
    http_response_code(405);
    header('Allow: ' . implode(', ', array_keys($routes[$action])));
    exit('Deze aanvraagmethode is niet toegestaan.');
}

session_start();

try {
    $controller = new ItemController(new Item(connectDatabase()));
    $controller->{$routes[$action][$method]}();
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit('De voorraad kan op dit moment niet worden geopend. Probeer het later opnieuw.');
}
