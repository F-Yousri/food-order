<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Product;
use Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_fails_on_unavailable_product_id()
    {
        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 2],
            ],
        ])->assertUnprocessable();
    }

    public function test_order_fails_on_wrong_quantity()
    {
        Product::factory()->create(['id' => 1]);

        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 'x'],
            ],
        ])->assertUnprocessable();
    }

    public function test_order_succeeds()
    {
        Product::factory()->create(['id' => 1]);
        Product::factory()->create(['id' => 2]);

        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 1],
                ['product_id' => 2, 'quantity' => 5],
            ],
        ])->assertNoContent();
    }

    public function test_it_returns_insuffecient_ingredients_response()
    {
        Product::factory()->hasAttached(Ingredient::factory(['stock' => 100]), ['weight' => 200])->create(['id' => 1]);

        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 1],
            ],
        ])->assertSee(['message' => 'The ingredients available are insuffecient to complete the order.']);
    }

    public function test_it_returns_high_demand_response()
    {
        $ingredient = Ingredient::factory(['stock' => 100])->create();
        Product::factory()->hasAttached($ingredient, ['weight' => 100])->create(['id' => 1]);
        $lock = Cache::lock("ingredient_{$ingredient->id}", 6);
        $lock->get();

        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 1],
            ],
        ])->assertStatus(503)->assertSee(['message' => 'We\'re experiencing exceptionally high demand. Please try again later.'], false);
        $lock->release();
    }
}
