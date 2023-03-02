<?php

namespace App\Observers;

use App\Models\ExpensePaymentMethod;

class ExpensePaymentMethodObserver
{
    /**
     * Handle the ExpensePaymentMethod "created" event.
     */
    public function created(ExpensePaymentMethod $expensePaymentMethod): void
    {
        //
    }

    /**
     * Handle the ExpensePaymentMethod "updated" event.
     */
    public function updated(ExpensePaymentMethod $expensePaymentMethod): void
    {
        //
    }

    /**
     * Handle the ExpensePaymentMethod "deleted" event.
     */
    public function deleted(ExpensePaymentMethod $expensePaymentMethod): void
    {
        //
    }

    /**
     * Handle the ExpensePaymentMethod "restored" event.
     */
    public function restored(ExpensePaymentMethod $expensePaymentMethod): void
    {
        //
    }

    /**
     * Handle the ExpensePaymentMethod "force deleted" event.
     */
    public function forceDeleted(ExpensePaymentMethod $expensePaymentMethod): void
    {
        //
    }
}
