<?php

namespace App\Http\Controllers;

use App\Enums\CategoryColorEnum;
use App\Enums\CategoryIconEnum;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Interfaces\CategoryInterface;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private CategoryInterface $categoryRepository, private CategoryService $categoryService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepository->getAllCategories();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colors = CategoryColorEnum::options();
        $icons = CategoryIconEnum::options();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory($category, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->expenses->isNotEmpty()) {
            return;
        }
        $category->delete();
    }
}
