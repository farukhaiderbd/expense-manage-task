<?php

namespace App\Observers;


use App\Models\ExpenseCategory;

class ExpenseCategoryObserver
{
    public function creating(ExpenseCategory $category)
        {
            if (auth()->check()) {
                $category->user_id = auth()->id();
            }
        }
}
