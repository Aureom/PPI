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
            $this->save_uploaded_image($this->images, '/assets/product/images', $product);
        }

        return $productService->createProduct($product);
    }

    function save_uploaded_image($image_file, $destination_folder, $product)
    {
        if ($image_file['error'] !== UPLOAD_ERR_OK) {
            echo 'Erro ao fazer upload: ' . $image_file['error'];
            return false;
        }

        $file_tmp_name = $image_file['tmp_name'];
        $file_name = basename($image_file['name']);
        $file_size = $image_file['size'];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $valid_types = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($file_type, $valid_types)) {
            echo 'Arquivo não permitido. Apenas JPG, JPEG, PNG e GIF.';
            return false;
        }

        if ($file_size > 5000000) { // limitado a 5MB
            echo 'O arquivo é muito grande.';
            return false;
        }

        $new_file_path = $destination_folder . '/' . $file_name;

        if (move_uploaded_file($file_tmp_name, $new_file_path)) {
            $product->addPhoto(new ProductPhoto(null, $new_file_path));
            return true;
        } else {
            $badRequest = new BadRequest("Erro ao fazer upload da imagem");
            echo $badRequest->toJson();
            exit;
        }
    }

}