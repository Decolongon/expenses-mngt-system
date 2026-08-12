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
        ->where('author_id', Auth::id())
        ->get();
    }
}
