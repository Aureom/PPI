<?php

require_once __DIR__ . "/../interfaces/errors/UnauthorizedRequest.php";
require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../security/Auth.php";
require_once __DIR__ . "/../repository/InterestRepository.php";
require_once __DIR__ . "/../repository/ProductRepository.php";

class InterestService
{
    private InterestRepository $interestRepository;
    private ProductService $productService;

    public function __construct(InterestRepository $interestRepository, ProductService $productRepository)
    {
        $this->interestRepository = $interestRepository;
        $this->productService = $productRepository;
    }

    public function addInterest($productId, $message, $phone): ?Interest
    {
        $product = $this->productService->findById($productId);

        if ($product === null) {
            $badRequest = new BadRequest("O produto não existe");
            echo $badRequest->toJson();
            return null;
        }

        return $this->interestRepository->save($productId, $message, $phone);
    }
}