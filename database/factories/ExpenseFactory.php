<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'                   => 1,
            'expense_category_id'                   => fake()->numberBetween(1, 5),
            'expense_payment_method_id'             => fake()->numberBetween(1, 5),
            'amount'                                => fake()->numberBetween(1000, 5000),
            'expense_date'                          => fake()->date(),
            'note'                                  => fake()->text(),
            'image'                                 => fake()->imageUrl()
        ];
    }
}
