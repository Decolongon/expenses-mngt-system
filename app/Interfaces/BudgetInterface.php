<?php

namespace App\Interfaces;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Collection;

interface BudgetInterface
{
    public function createBudget(array $data): Budget;

    public function updateBudget(Budget $budget, array $data): Budget;

    public function getBudget(): Collection;
}
