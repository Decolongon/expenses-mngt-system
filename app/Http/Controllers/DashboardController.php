<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index(): Response
    {
        $now = now();

        return Inertia::render('Dashboard', [
            'stats' => $this->dashboardService->getStats((int) $now->month, (int) $now->year),
            'monthlySpending' => $this->dashboardService->getMonthlySpending(6),
            'spendingByCategory' => $this->dashboardService->getSpendingByCategory((int) $now->month, (int) $now->year),
            'budgetProgress' => $this->dashboardService->getBudgetProgress((int) $now->month, (int) $now->year),
            'recentExpenses' => $this->dashboardService->getRecentExpenses(8),
            'recurringExpenses' => $this->dashboardService->getRecurringExpenses(),
        ]);
    }
}