<?php

namespace App\Enums;

use App\Concerns\HasEnumOptions;

enum ExpenseTypeEnum: string
{
    use HasEnumOptions;
    
    case OneTime = 'one-time';
    case Recurring = 'recurring';
}
