<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpensePaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirOwnExpense()
        {
        $john           = User::factory()->create();
        $category= ExpenseCategory::factory()->create([
            'user_id' => $john->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name'=>'Bkash',
                'image'=> UploadedFile::fake()->image('bkash_logo.jpg')
            ]
        );
        $expense = Expense::factory()->create([
            'user_id' => $john->id,
            'expense_category_id' => $category->id,
            'expense_payment_method_id' => $paymentMethod->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response = $this->actingAs($john)->getJson('/api/v1/expenses');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', $expense->name);
        }

    public function testUserCanCreateExpense()
        {
        $user = User::factory()->create();

        $category= ExpenseCategory::factory()->create([
            'user_id' => $user->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name'=>'Bkash',
                'image'=> UploadedFile::fake()->image('bkash_logo.jpg')
            ]
        );

        $response = $this->actingAs($user)->postJson('/api/v1/expenses', [
            'user_id' => $user->id,
            'expense_category_id' => $category->id,
            'expense_payment_method_id' => $paymentMethod->id,
            'amount'                                => fake()->numberBetween(1000, 5000),
            'expense_date'                          => fake()->date(),
            'note'                                  => fake()->text(),
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(7, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'expense_category_id'],
            ])
            ->assertJsonPath('data.expense_category_id', $category->id);

        $this->assertDatabaseHas('expenses', [
            'expense_category_id' => $category->id,
        ]);
        }

    public function testUserCanUpdateTheirExpense()
        {
        $user = User::factory()->create();

        $category= ExpenseCategory::factory()->create([
            'user_id' => $user->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name'=>'Bkash',
                'image'=> UploadedFile::fake()->image('bkash_logo.jpg')
            ]
        );
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'expense_category_id' => $category->id,
            'expense_payment_method_id' => $paymentMethod->id,
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/expenses/'.$expense->id, [
            'user_id' => $user->id,
            'expense_category_id' => $category->id,
            'expense_payment_method_id' => $paymentMethod->id,
            'amount'                                => fake()->numberBetween(1000, 5000),
            'expense_date'                          => fake()->date(),
            'note'                                  => fake()->text(),
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['expense_category_id'])
            ->assertJsonPath('expense_category_id', $category->id);

        $this->assertDatabaseHas('expenses', [
            'expense_category_id' => $category->id,
        ]);
        }

    public function testUserCanDeleteTheirExpense()
        {
        $john           = User::factory()->create();
        $category= ExpenseCategory::factory()->create([
            'user_id' => $john->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name'=>'Bkash',
                'image'=> UploadedFile::fake()->image('bkash_logo.jpg')
            ]
        );
        $expense = Expense::factory()->create([
            'user_id' => $john->id,
            'expense_category_id' => $category->id,
            'expense_payment_method_id' => $paymentMethod->id,
            'amount'                                => fake()->numberBetween(1000, 5000),
            'expense_date'                          => fake()->date(),
            'note'                                  => fake()->text(),
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response = $this->actingAs($john)->deleteJson('/api/v1/expenses/'.$expense->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
            'deleted_at' => NULL
        ])->assertDatabaseCount('expenses', 1);
        }
}
