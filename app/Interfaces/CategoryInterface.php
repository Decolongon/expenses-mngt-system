<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CategoryInterface
{
    public function getAllCategories(): Collection;
}
