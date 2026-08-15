<?php

namespace App\Interfaces;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

interface ExpensesInterface
{
    public function getAllExpenses(): Collection;

    public function createExpenses(array $data): Expense;

    public function updateExpenses(Expense $expense, array $data): Expense;
}
