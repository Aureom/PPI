<?php

require_once __DIR__ . "/../controller/ProductController.php";
require_once __DIR__ . "/../repository/ProductRepository.php";

$mysqlConnector = new MySQLConnector();
$productRepository = new ProductRepository($mysqlConnector);
$productController = new ProductController($productRepository);

// Check the URL of the request
$path = $_SERVER['PATH_INFO'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/my-products') {
    echo $productController->getProductsForLoggedInUser();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
