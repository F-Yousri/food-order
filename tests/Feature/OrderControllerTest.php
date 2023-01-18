<?php

namespace Tests\Feature;

use App\Models\Order;
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
        Order::factory()->create(['id' => 1]);

        $this->json('POST', route('orders.store'), [
            'products' => [
                ['product_id' => 1, 'quantity' => 'x'],
            ],
        ])->assertUnprocessable();
    }
}
