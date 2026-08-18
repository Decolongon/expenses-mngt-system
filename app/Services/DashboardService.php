<?php

namespace App\Services;

use App\Interfaces\DashboardInterface;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(private DashboardInterface $dashboardRepository)
    {
    }

    public function getStats(int $month, int $year): array
    {
        return $this->dashboardRepository->getStats($month, $year);
    }

    public function getMonthlySpending(int $months): array
    {
        return $this->dashboardRepository->getMonthlySpending($months);
    }

    public function getSpendingByCategory(int $month, int $year): array
    {
        return $this->dashboardRepository->getSpendingByCategory($month, $year);
    }

    public function getBudgetProgress(int $month, int $year): array
    {
        return $this->dashboardRepository->getBudgetProgress($month, $year);
    }

    public function getRecentExpenses(int $limit = 8): Collection
    {
        return $this->dashboardRepository->getRecentExpenses($limit);
    }

    public function getRecurringExpenses(): Collection
    {
        return $this->dashboardRepository->getRecurringExpenses();
    }
}
