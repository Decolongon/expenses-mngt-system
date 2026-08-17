<?php

namespace App\Enums;

use App\Concerns\HasEnumOptions;

enum RecurringIntervalExpenseEnum: string
{
    use HasEnumOptions;
    
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';

}
