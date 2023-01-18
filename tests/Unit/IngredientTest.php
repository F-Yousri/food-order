<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_consume()
    {
        $ingredient = Ingredient::factory()->make(['stock' => 150]);
        $ingredient->consume(100);

        $this->assertEquals(50, $ingredient->stock);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredient->id, 'stock' => 50]);
    }
}
