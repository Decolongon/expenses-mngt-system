<?php

namespace App\Concerns;

trait BudgetVallidationRules
{
    protected function budgetRules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'amount' => [
                'required',
                'numeric',
            ],
            'year' => [
                'required',
                'numeric',
            ],
            'month' => [
                'required',
                'numeric',
            ],
        ];
    }
}
