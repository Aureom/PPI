<?php

require_once __DIR__ . "/../interfaces/errors/UnauthorizedRequest.php";
require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../repository/ProductRepository.php";
require_once __DIR__ . "/../repository/ProductPhotoRepository.php";
require_once __DIR__ . "/../security/Auth.php";

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductsForLoggedInUser(): array
    {
        $user = Auth::user(); // get the logged in user

        if ($user === null) {
            $badRequest = new UnauthorizedRequest("Você precisa estar logado para ver seus anúncios");
            echo $badRequest->toJson();
            return [];
        }

        return $this->productRepository->findAllByUserId($user->getId());
    }

    public function findRecentProducts(int $offset = 0): array
    {
        return $this->productRepository->findAllMostRecent($offset);
    }

    public function createProduct(Product $product): ?Product
    {
        $user = Auth::user(); // get the logged-in user

        if ($user === null) {
            $badRequest = new UnauthorizedRequest("Você precisa estar logado para criar um anúncio");
            echo $badRequest->toJson();
            return null;
        }

        try {
            $product->setUserId($user->getId());
            return $this->productRepository->save($product);
        } catch (Exception $e) {
            $badRequest = new BadRequest($e->getMessage());
            echo $badRequest->toJson();
        }

        return null;
    }

    public function deleteProduct(int $productId): void
    {
        $user = Auth::user(); // get the logged-in user

        if ($user === null) {
            $badRequest = new UnauthorizedRequest("Você precisa estar logado para deletar um anúncio");
            echo $badRequest->toJson();
            return;
        }

        $product = $this->productRepository->findById($productId);

        if ($product === null) {
            $badRequest = new BadRequest("O produto não existe");
            echo $badRequest->toJson();
            return;
        }

        if ($product->getUserId() !== $user->getId()) {
            $badRequest = new UnauthorizedRequest("Você não tem permissão para deletar esse produto");
            echo $badRequest->toJson();
            return;
        }

        $this->productRepository->delete($product);
    }

    public function addInterest($productId, $message, $phone): ?Interest
    {
        $product = $this->productRepository->findById($productId);

        if ($product === null) {
            $badRequest = new BadRequest("O produto não existe");
            echo $badRequest->toJson();
            return null;
        }

        return $this->interestRepository->save($productId, $message, $phone);
    }

    public function findById($productId): ?Product
    {
        return $this->productRepository->findById($productId);
    }
}