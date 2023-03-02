<?php

namespace Tests\Feature;

use App\Models\ExpensePaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExpensePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirOwnExpensePaymentMethod()
        {
        Storage::fake('images');
        $user = User::factory()->create();
        $paymentMethodBkash = ExpensePaymentMethod::factory()->create(
            [
                'name'=>'Bkash',
                'image'=> UploadedFile::fake()->image('bkash_logo.jpg')
            ]
        );
        $response = $this->actingAs($user)->getJson('/api/v1/expense-payment-methods');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', $paymentMethodBkash->name);
        }

    public function testUserCanCreateExpensePaymentMethod()
        {
        Storage::fake('images');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/expense-payment-methods', [
            'name' => 'Nagad',
            'image'=> UploadedFile::fake()->image('Nagad_logo.jpg')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'name'],
            ])
            ->assertJsonPath('data.name', 'Nagad');

        $this->assertDatabaseHas('expense_payment_methods', [
            'name' => 'Nagad',
        ]);
        }

    public function testUserCanUpdateTheirExpensePaymentMethod()
        {
        $user    = User::factory()->create();
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name' => 'Bank',
                'image'=> UploadedFile::fake()->image('bank_logo.jpg')
            ]
        );

        $response = $this->actingAs($user)->putJson('/api/v1/expense-payment-methods/'.$paymentMethod->id, [
            'name'       => 'Bank New',
            'image'=> UploadedFile::fake()->image('bank_logo.jpg')
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['name'])
            ->assertJsonPath('name', 'Bank New');

        $this->assertDatabaseHas('expense_payment_methods', [
            'name' => 'Bank New',
        ]);
        }

    public function testUserCanDeleteTheirExpensePaymentMethod()
        {
        $user    = User::factory()->create();
        $paymentMethod = ExpensePaymentMethod::factory()->create(
            [
                'name' => 'Bank',
                'image'=> UploadedFile::fake()->image('bank_logo.jpg')
            ]
        );

        $response = $this->actingAs($user)->deleteJson('/api/v1/expense-payment-methods/'.$paymentMethod->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('expense_payment_methods', [
            'id' => $paymentMethod->id,
            'deleted_at' => NULL
        ])->assertDatabaseCount('expense_payment_methods', 1);
        }
}
