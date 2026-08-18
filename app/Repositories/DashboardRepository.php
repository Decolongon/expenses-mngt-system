<?php

namespace App\Repositories;

use App\Interfaces\DashboardInterface;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardRepository implements DashboardInterface
{
    public function getStats(int $month, int $year): array
    {
        $totalSpent = (float) Expense::query()
            ->where('author_id', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalBudget = (float) Budget::query()
            ->where('author_id', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->sum('amount');

        return [
            'totalSpent' => $totalSpent,
            'totalBudget' => $totalBudget,
            'remaining' => max($totalBudget - $totalSpent, 0),
            'expenseCount' => (int) Expense::query()
                ->where('author_id', Auth::id())
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count(),
            'categoryCount' => (int) Category::query()
                ->where('author_id', Auth::id())
                ->count(),
        ];
    }

    public function getMonthlySpending(int $months): array
    {
        $spending = Expense::query()
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') AS month_label, SUM(amount) AS total")
            ->where('author_id', Auth::id())
            ->where('date', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->groupBy('month_label')
            ->orderBy('month_label')
            ->pluck('total', 'month_label');

        $result = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $result[] = [
                'label' => $date->format('M'),
                'total' => (float) ($spending[$date->format('Y-m')] ?? 0),
            ];
        }

        return $result;
    }

    public function getSpendingByCategory(int $month, int $year): array
    {
        $rows = Expense::query()
            ->selectRaw("COALESCE(category_id, '') AS category_key, SUM(amount) AS total")
            ->where('author_id', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupByRaw("COALESCE(category_id, '')")
            ->orderByDesc('total')
            ->get()
            ->keyBy('category_key');

        $categories = Category::query()
            ->where('author_id', Auth::id())
            ->get(['id', 'name', 'color', 'icon'])
            ->keyBy('id');

        $result = [];

        foreach ($rows as $categoryKey => $row) {
            $category = $categoryKey ? $categories->get($categoryKey) : null;

            $result[] = [
                'id' => $category?->id,
                'name' => $category?->name ?? 'Uncategorized',
                'color' => $category?->color,
                'icon' => $category?->icon,
                'total' => (float) $row->total,
            ];
        }

        $total = array_sum(array_column($result, 'total'));

        foreach ($result as &$item) {
            $item['percent'] = $total > 0 ? round(($item['total'] / $total) * 100, 1) : 0;
        }

        unset($item);

        return $result;
    }

    public function getBudgetProgress(int $month, int $year): array
    {
        $budgets = Budget::query()
            ->with('category:id,name,color,icon')
            ->where('author_id', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        if ($budgets->isEmpty()) {
            return [];
        }

        $spendingByCategory = Expense::query()
            ->selectRaw("COALESCE(category_id, 'uncategorized') AS category_key, SUM(amount) AS total")
            ->where('author_id', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupByRaw("COALESCE(category_id, 'uncategorized')")
            ->pluck('total', 'category_key');

        return $budgets->map(function (Budget $budget) use ($spendingByCategory) {
            $amount = (float) $budget->amount;
            $spent = (float) ($spendingByCategory[$budget->category_id ?? 'uncategorized'] ?? 0);

            return [
                'id' => $budget->id,
                'amount' => $amount,
                'spent' => $spent,
                'percent' => $amount > 0 ? round(($spent / $amount) * 100, 1) : 0,
                'category' => [
                    'id' => $budget->category?->id,
                    'name' => $budget->category?->name ?? 'Uncategorized',
                    'color' => $budget->category?->color,
                    'icon' => $budget->category?->icon,
                ],
            ];
        })->values()->all();
    }

    public function getRecentExpenses(int $limit = 8): Collection
    {
        return Expense::query()
            ->with('category:id,name,color,icon')
            ->where('author_id', Auth::id())
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getRecurringExpenses(): Collection
    {
        return Expense::query()
            ->with('category:id,name,color,icon')
            ->where('author_id', Auth::id())
            ->where('type', 'recurring')
            ->orderByDesc('recurring_start_date')
            ->limit(10)
            ->get();
    }
}
