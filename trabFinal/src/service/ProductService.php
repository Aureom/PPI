<?php
class ProductService {
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getProductsForLoggedInUser(): string {
        $user = Auth::user(); // get the logged in user

        if ($user === null) {
            $badRequest = new UnauthorizedRequest("Você precisa estar logado para ver seus anúncios");
            return json_encode($badRequest, JSON_THROW_ON_ERROR);
        }

        $ads = $this->productRepository->findAllByUserId($user->getId());

        return json_encode($ads, JSON_THROW_ON_ERROR);
    }
}