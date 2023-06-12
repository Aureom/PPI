<?php

class CategoryService {
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function findAll(): array {
        return $this->categoryRepository->findAll();
    }

    public function create(Category $category): void {
        $this->categoryRepository->create($category);
    }
}
