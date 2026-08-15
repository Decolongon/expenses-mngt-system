<?php

namespace App\Repositories;

use App\Interfaces\BudgetInterface;
use App\Models\Budget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BudgetRepository implements BudgetInterface
{
   
    public function createBudget(array $data): Budget
    {
        $data['author_id'] = Auth::id();
        return Budget::create($data);
    }

    public function updateBudget(Budget $budget, array $data): Budget
    {
        $budget->update($data);
        return $budget;
    }

    public function getBudget(): Collection
    {
       return Budget::query()
       ->with(['author:id,name', 'category:id,name,color,icon'])
       ->where('author_id', Auth::id())
       ->get();
    }
}
