<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakingAProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_consumes_ingredients()
    {
        $ingredients = Ingredient::factory()->count(3)
            ->create(['stock' => 400, 'recommended_stock' => 50000]);

        $product = Product::factory()
          ->hasAttached(
              $ingredients,
              ['weight' => 200],
          )->create();

        $product->prepare();

        $ingredients->each(
            fn ($ingredient) => $this->assertDatabaseHas('ingredients', ['id' => $ingredient->id, 'stock' => 200]));
    }
}
