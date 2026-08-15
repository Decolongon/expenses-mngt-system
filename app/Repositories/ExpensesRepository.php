<?php

namespace App\Repositories;

use App\Interfaces\ExpensesInterface;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ExpensesRepository implements ExpensesInterface
{
    public function getAllExpenses(): Collection
    {
        return Expense::query()
            ->with(['author:id,name', 'category:id,name,color,icon'])
            ->where('author_id', Auth::id())
            ->get();
    }

    public function createExpenses(array $data): Expense
    {
        $data['author_id'] = Auth::id();

        return Expense::create($data);
    }

    public function updateExpenses(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        return $expense;
    }
}
