<?php

require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../service/ProductService.php";
require_once __DIR__ . "/../repository/ProductRepository.php";
require_once __DIR__ . "/../requests/CreateProductRequest.php";

$mysqlConnector = new MySQLConnector();
$productRepository = new ProductRepository($mysqlConnector);
$productService = new ProductService($productRepository);

// Check the URL of the request
$path = $_SERVER['PATH_INFO'] ?? '';
$request = $_SERVER['REQUEST_METHOD'] ?? '';

if ($request === 'POST' && $path === '/create') {
    try {
        $createProduct = new CreateProductRequest();
        $product = $createProduct->validateFieldsAndSave($productService);

        if ($product === null) {
            http_response_code(400);
            $response = new BadRequest("Ocorreu um erro ao tentar cadastrar o produto");
            echo $response->toJson();
            exit;
        }

        http_response_code(201);
        header('HTTP/1.1 201 Created'); // Por algum motivo só o http_response_code não estava funcionando
        header('Content-Type: application/json');
        echo json_encode($product);
    } catch (JsonException $e) {
        $response = new BadRequest("Ocorreu um erro ao tentar cadastrar o produto");
        echo $response->toJson();
    }
}
if ($request === 'GET' && $path === '/list') {
    // Example /list?page=1 (page is optional)
    $page = $_GET['page'] ?? 0;
    $products = $productService->findRecentProducts($page * 10);
    echo json_encode($products);
} else if ($request === 'DELETE' && $path === '/delete') {
    $productId = $_GET['id'] ?? null;
    if (!is_numeric($productId)) {
        $response = new BadRequest("O id do produto precisa ser um número");
        echo $response->toJson();
        exit;
    }
    $productService->deleteProduct($productId);
} else {
    http_response_code(404);
    exit;
}
