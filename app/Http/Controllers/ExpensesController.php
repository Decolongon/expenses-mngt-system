<?php

namespace App\Http\Controllers;

use App\Concerns\HasFlashMessage;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\CategoryService;
use App\Services\ExpensesService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpensesController extends Controller
{
    use HasFlashMessage;

    public function __construct(private ExpensesService $expensesService, private CategoryService $categoryService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('expenses/expenses-index', [
            'expenses' => $this->expensesService->getAllExpenses(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('expenses/expenses-create',[
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateExpenseRequest $request)
    {
        $this->expensesService->createExpenses($request->validated());
        $this->successMessage('Expense created successfully.');
        return to_route('expenses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        abort_if($expense->author_id !== Auth::id(), 403, 'You are not authorized to edit this expense.');
        $expense->load(['category:id,name']);
        return Inertia::render('expenses/expenses-edit',[
            'categories' => $this->categoryService->getCategories(),
            'expense' => $expense
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        abort_if($expense->author_id !== Auth::id(), 403, 'You are not authorized to update this expense.');
        $this->expensesService->updateExpenses($expense, $request->validated());
        $this->successMessage('Expense updated successfully.');
        return to_route('expenses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        abort_if($expense->author_id !== Auth::id(), 403, 'You are not authorized to delete this expense.');
        $expense->delete();
        $this->successMessage('Expense deleted successfully.');
        return to_route('expenses.index');
    }
}
