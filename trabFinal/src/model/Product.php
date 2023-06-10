<?php 

class Product {
    protected int $id;
    protected string $name;
    protected string $description;
    protected int $price;
    protected string $image;

    public function __construct($id, $name, $description, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = "https://picsum.photos/seed/$id/200/300";
    }

    public static function mock(): Product {
        $id = random_int(1, 1000);
        return new self($id, "Product $id", "Description of Product $id", random_int(1000, 100000));
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getFormattedPrice(): string {
        return "R$ " . number_format($this->price / 100, 2, ",", ".");
    }

    public function getImageUrl(): string {
        return $this->image;
    }
}