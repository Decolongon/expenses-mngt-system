<?php

namespace App\Services;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    public function __construct(private CategoryInterface $categoryRepository) {}

    public function createCategory(array $data): Category
    {

        return $this->categoryRepository->createCategory($data);
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function updateCategory(Category $category, array $data): Category
    {
        return $this->categoryRepository->updateCategory($category, $data);
    }
}
