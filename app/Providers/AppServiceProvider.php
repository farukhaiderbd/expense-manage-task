<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Observers\ExpenseCategoryObserver;
use App\Observers\ExpenseObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ExpenseCategory::observe(ExpenseCategoryObserver::class);
        Expense::observe(ExpenseObserver::class);
    }
}
