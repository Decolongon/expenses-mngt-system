<?php

namespace App\Http\Controllers;

use App\Concerns\HasFlashMessage;
use App\Http\Requests\Budget\CreateBudgetRequest;
use App\Http\Requests\Budget\UpdateBudgetRequest;
use App\Models\Budget;
use App\Services\BudgetService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BudgetController extends Controller
{
    use HasFlashMessage;

    public function __construct(private BudgetService $budgetService, private CategoryService $categoryService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('budget/budget-index', [
            'budgets' => $this->budgetService->getBudget(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('budget/budget-create', [
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBudgetRequest $request)
    {
        $this->budgetService->createBudget($request->validated());
        $this->successMessage('Budget created successfully.');
        return to_route('budgets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        abort_if($budget->author_id !== Auth::id(), 403, 'You are not authorized to edit this budget.');
        $budget->load(['category:id,name' , 'author:id,name']);
        return Inertia::render('budget/budget-edit', [
            'budget' => $budget,
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        abort_if($budget->author_id !== Auth::id(), 403, 'You are not authorized to edit this budget.');
        $this->budgetService->updateBudget($budget, $request->validated());
        $this->successMessage('Budget updated successfully.');
        return to_route('budgets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        abort_if($budget->author_id !== Auth::id(), 403, 'You are not authorized to delete this budget.');
        $budget->delete();
        $this->successMessage('Budget deleted successfully.');
        return to_route('budgets.index');
    }
}
