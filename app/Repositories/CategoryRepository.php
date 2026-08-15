<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CategoryRepository implements CategoryInterface
{
    public function getAllCategories(): Collection
    {
        return Category::query()
            ->with('author:id,name')
            ->where('author_id', Auth::id())
            ->get();
    }

    public function createCategory(array $data): Category
    {
        $data['author_id'] = Auth::id();
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }
}
