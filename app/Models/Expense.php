<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'category_id',
    'author_id',
    'amount', 
    'title',
    'description',
    'date',
    'type',
    'recurring_interval',
    'recurring_start_date',
    'recurring_end_date',
    'is_auto_generated'
    )]
class Expense extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
