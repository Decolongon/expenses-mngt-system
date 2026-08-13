<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryInterface
{
    public function getAllCategories(): Collection;
    
    public function createCategory(array $data): Category;

    public function updateCategory(Category $category, array $data): Category;
}
