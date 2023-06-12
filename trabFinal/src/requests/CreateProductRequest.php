<?php

require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../model/Product.php";
require_once __DIR__ . "/../model/ProductPhoto.php";

class CreateProductRequest
{
    private string $title;
    private string $description;
    private int $price;
    private string $zipCode;
    private string $neighborhood;
    private string $city;
    private string $state;
    private int $categoryId;
    private array $images;

    public function __construct()
    {
        $this->title = $_POST['title'] ?? null;
        $this->description = $_POST['description'] ?? null;
        $this->price = $_POST['price'] ?? null;
        $this->zipCode = $_POST['zipCode'] ?? null;
        $this->neighborhood = $_POST['neighborhood'] ?? null;
        $this->city = $_POST['city'] ?? null;
        $this->state = $_POST['state'] ?? null;
        $this->categoryId = $_POST['categoryId'] ?? null;
        $this->images = $_FILES['images'] ?? [];
    }

    public function validateFieldsAndSave(ProductService $productService): ?Product
    {
        if (empty($this->title) || empty($this->description) || empty($this->price) || empty($this->zipCode) || empty($this->neighborhood) || empty($this->city) || empty($this->state) || empty($this->categoryId)) {
            $badRequest = new BadRequest("Todos os campos são obrigatórios");
            echo $badRequest->toJson();
            exit;
        }

        if (strlen($this->zipCode) !== 8) {
            $badRequest = new BadRequest("O CEP deve ter 8 dígitos");
            echo $badRequest->toJson();
            exit;
        }

        // Validate images (can be optional)
        if (!empty($this->images) && !$this->validateImages()) {
            $badRequest = new BadRequest("Erro ao fazer upload da imagem");
            echo $badRequest->toJson();
            exit;
        }

        $product = new Product(
            null,
            $this->title,
            $this->description,
            $this->price,
            $this->zipCode,
            $this->neighborhood,
            $this->city,
            $this->state,
            1,
            1,
            new DateTime(),
            []
        );

        if (!empty($this->images)) {
            $this->saveImages($product);
        }

        return $productService->createProduct($product);
    }

    private function saveImages(Product $product): void
    {
        foreach ($this->images['tmp_name'] as $index => $tmpName) {
            $fileName = $this->images['name'][$index];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);


            if (is_uploaded_file($tmpName)) {
                $fileName = uniqid('photo_', true) . ".$fileExtension";
                if (move_uploaded_file($tmpName, __DIR__ . "/../images/$fileName")) {
                    $product->addPhoto(new ProductPhoto(null, $fileName));
                } else {
                    $badRequest = new BadRequest("Erro ao fazer upload da imagem");
                    echo $badRequest->toJson();
                    exit;
                }
            }
        }
    }


    private function validateImages(): bool
    {
        $images = $this->images;
        $imagesSize = $images['size'];
        $imagesName = $images['name'];
        $imagesError = $images['error'];
        $imagesType = $images['type'];

        $success = true;
        foreach ($this->images['tmp_name'] as $i => $tmpName) {
            $imageSize = $imagesSize[$i];
            $imageName = $imagesName[$i];
            $imageError = $imagesError[$i];

            if ($imageError !== 0) {
                $badRequest = new BadRequest("Erro ao fazer upload da imagem");
                $success = false;
                echo $badRequest->toJson();
                break;
            }

            if ($imageSize > 1000000) {
                $badRequest = new BadRequest("A imagem deve ter no máximo 1MB");
                $success = false;
                echo $badRequest->toJson();
                break;
            }

            $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg") {
                $badRequest = new BadRequest("A imagem deve ser JPG, JPEG ou PNG");
                $success = false;
                echo $badRequest->toJson();
                break;
            }
        }

        return $success;
    }


}