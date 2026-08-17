<?php

namespace App\Concerns;

use App\Enums\ExpenseTypeEnum;
use App\Enums\RecurringIntervalExpenseEnum;
use Illuminate\Validation\Rule;

trait ExpenseValidationRules
{
    protected function expenseRules(): array
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
            'date' => [
                'required',
                'date',
            ],
            'description' => [
                'required',
                'string',
                'max:100',
                'min:5',
            ],
            'type' => [
                'required',
                Rule::enum(ExpenseTypeEnum::class),
            ],
            'recurring_interval' => [
                'required_if:type,recurring',
                Rule::enum(RecurringIntervalExpenseEnum::class),
            ],
            'recurring_start_date' => [
                'required_if:type,recurring',
                'date',
            ],
            'recurring_end_date' => [
                'required_if:type,recurring',
                'date',
                'after:recurring_start_date',
            ],
            'is_auto_generated' => [
                'required_if:type,recurring',
                'boolean',
            ],

        ];
    }
}
