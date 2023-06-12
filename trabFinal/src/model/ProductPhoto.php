<?php

class ProductPhoto
{
    protected ?int $productId;
    protected string $photoUri;


    public function __construct(?int $productId, string $photoUri)
    {
        $this->productId = $productId;
        $this->photoUri = $photoUri;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getPhotoUri(): string
    {
        return $this->photoUri;
    }

}