<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface DashboardInterface
{
    public function getStats(int $month, int $year): array;

    public function getMonthlySpending(int $months): array;

    public function getSpendingByCategory(int $month, int $year): array;

    public function getBudgetProgress(int $month, int $year): array;

    public function getRecentExpenses(int $limit = 8): Collection;

    public function getRecurringExpenses(): Collection;
}
