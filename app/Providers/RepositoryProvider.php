<?php

namespace App\Providers;

use App\Interfaces\BudgetInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\ExpensesInterface;
use App\Repositories\BudgetRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ExpensesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindInterfaces();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
    protected function bindInterfaces()
    {
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            BudgetInterface::class,
            BudgetRepository::class
        );

        $this->app->bind(
            ExpensesInterface::class,
            ExpensesRepository::class
        );
    }
}
