<?php

namespace App\Concerns;

use App\Enums\CategoryColorEnum;
use App\Enums\CategoryIconEnum;
use Illuminate\Validation\Rule;

trait CategoryValidationRules
{
    protected function categoryRules(): array
    {
        $category = $this->route('category');
        
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'min:3',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'color' => [
                'nullable',
                'string',
                Rule::enum(CategoryColorEnum::class)
            ],
            'icon' => [
                'nullable',
                'string',
                Rule::enum(CategoryIconEnum::class),
            ],
        ];
    }
}
