<?php

namespace Tests\Feature;

use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExpenseCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirOwnExpenseCategory()
        {
        $john           = User::factory()->create();
        $categoryForJohn = ExpenseCategory::factory()->create([
            'user_id' => $john->id,
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response = $this->actingAs($john)->getJson('/api/v1/expense-categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', $categoryForJohn->name);
        }

    public function testUserCanCreateExpenseCategory()
        {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/expense-categories', [
            'user_id' => $user->id,
            'name' => 'House Rent',
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'name'],
            ])
            ->assertJsonPath('data.name', 'House Rent');

        $this->assertDatabaseHas('expense_categories', [
            'name' => 'House Rent',
        ]);
        }

    public function testUserCanUpdateTheirExpenseCategory()
        {
        $user    = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson('/api/v1/expense-categories/'.$category->id, [
            'user_id'    =>$user->id,
            'name'       => 'House Rent Update',
            'image'=> UploadedFile::fake()->image('category_image.jpg')
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['name'])
            ->assertJsonPath('name', 'House Rent Update');

        $this->assertDatabaseHas('expense_categories', [
            'name' => 'House Rent Update',
        ]);
        }

    public function testUserCanDeleteTheirExpenseCategory()
        {
        $user    = User::factory()->create();
        $category = ExpenseCategory::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/v1/expense-categories/'.$category->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('expense_categories', [
            'id' => $category->id,
            'deleted_at' => NULL
        ])->assertDatabaseCount('expense_categories', 1);
        }
}
