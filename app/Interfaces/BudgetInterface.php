<?php

namespace App\Interfaces;

use App\Models\Budget;

interface BudgetInterface
{
    public function createBudget(array $data): Budget;

    public function updateBudget(Budget $budget, array $data): Budget;
}
