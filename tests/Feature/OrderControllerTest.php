<?php

namespace Tests\Feature;

use App\Models\Product;
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
}
