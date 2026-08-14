<?php

namespace App\Services;

use App\Interfaces\BudgetInterface;
use App\Models\Budget;

class BudgetService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private BudgetInterface $budgetRepository){}
   
    public function createBudget(array $data): Budget
    {
        return $this->budgetRepository->createBudget($data);
    }

    public function updateBudget(Budget $budget, array $data): Budget
    {
        return $this->budgetRepository->updateBudget($budget, $data);
    }
}
