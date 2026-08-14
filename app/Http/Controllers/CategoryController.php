<?php

namespace App\Http\Controllers;

use App\Concerns\HasFlashMessage;
use App\Enums\CategoryColorEnum;
use App\Enums\CategoryIconEnum;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Inertia\Inertia;

class CategoryController extends Controller
{
    use HasFlashMessage;

    public function __construct(private CategoryService $categoryService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('category/category-index', [
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('category/category-create', [
            'colors' => CategoryColorEnum::options(),
            'icons' => CategoryIconEnum::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());
        $this->successMessage('Category created successfully.');
        return to_route('categories.index');
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
        return Inertia::render('category/category-edit', [
            'category' => $category,
            'colors' => CategoryColorEnum::options(),
            'icons' => CategoryIconEnum::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory($category, $request->validated());
        $this->successMessage('Category updated successfully.');
        return to_route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->expenses->isNotEmpty()) {
            $this->errorMessage('Cannot delete category that has expenses.');
            return;
        }
        $this->successMessage('Category deleted successfully.');
        $category->delete();

        return to_route('categories.index');
    }
}
