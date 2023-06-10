<?php
class ProductService {
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function getProductsForLoggedInUser(): string {
        $user = Auth::user(); // get the logged in user

        if ($user === null) {
            http_response_code(401); // Unauthorized
            return json_encode(['error' => 'Not logged in']);
        }

        $ads = $this->productRepository->getAdsByAdvertiserId($user->id);

        return json_encode($ads);
    }
}