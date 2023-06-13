<?php

class Product implements JsonSerializable
{
    protected ?int $id;
    protected string $title;
    protected string $description;
    protected int $price; // Divisible by 100
    protected string $zipCode;
    protected string $neighborhood;
    protected string $city;
    protected string $state;
    protected int $categoryId;
    protected int $userId;
    protected DateTime $createdAt;
    protected array $images;
    protected array $interests;

    public function __construct(?int     $id,
                                string   $title,
                                string   $description,
                                int      $price,
                                string   $zipCode,
                                string   $neighborhood,
                                string   $city,
                                string   $state,
                                int      $categoryId,
                                int      $userId,
                                DateTime $createdAt,
                                array    $image = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->zipCode = $zipCode;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
        $this->images = $image;
    }


    public static function mock(): Product
    {
        $id = random_int(1, 1000);
        return new self(
            $id,
            "Product $id",
            "Description of Product $id",
            random_int(1000, 100000),
            "12345678",
            "Bairro",
            "Cidade",
            "MG",
            1,
            0,
            new DateTime(),
            []
        );
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return "R$ " . number_format($this->price / 100, 2, ",", ".");
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getMainImage(): ?ProductPhoto
    {
        if (count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }

    public function hasImage(): bool
    {
        return count($this->images) > 0;
    }

    public function hasInterest(): bool
    {
        return count($this->interests) > 0;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function setNeighborhood(string $neighborhood): void
    {
        $this->neighborhood = $neighborhood;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function addPhoto(ProductPhoto $photo): void
    {
        $this->images[] = $photo;
    }

    public function setImages(array $productPhotos): void
    {
        $this->images = $productPhotos;
    }

    public function setInterests(array $interests): void
    {
        $this->interests = $interests;
    }

    public function getInterests(): array
    {
        return $this->interests;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "price" => $this->price,
            "zipCode" => $this->zipCode,
            "neighborhood" => $this->neighborhood,
            "city" => $this->city,
            "state" => $this->state,
            "categoryId" => $this->categoryId,
            "userId" => $this->userId,
            "createdAt" => $this->createdAt->format("Y-m-d H:i:s"),
            "images" => $this->images,
            "interests" => $this->interests
        ];
    }
}