<?php

namespace App\Services;

use App\Interfaces\ExpensesInterface;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class ExpensesService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ExpensesInterface $expensesRepository)
    {
        //
    }

    public function getAllExpenses(): Collection
    {
        return $this->expensesRepository->getAllExpenses();
    }

    public function createExpenses(array $data): Expense
    {
        return $this->expensesRepository->createExpenses($data);
    }

    public function updateExpenses(Expense $expense, array $data): Expense
    {
        return $this->expensesRepository->updateExpenses($expense, $data);
    }
}
