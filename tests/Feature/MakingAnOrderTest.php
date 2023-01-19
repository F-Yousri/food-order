<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Exceptions\InsuffecientIngredientsException;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakingAnOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fails_on_insuffecient_ingredients()
    {
        $ingredients = Ingredient::factory()->count(3)
            ->create(['stock' => 200, 'recommended_stock' => 50000]);

        $products = Product::factory()
          ->hasAttached(
              $ingredients,
              ['weight' => 200],
          )->count(2)->create();

        $order = new Order();
        $order->status = OrderStatus::Preparing;
        $order->save();
        $order->products()->sync($products->MapWithKeys(fn ($product) => [$product->id => ['quantity' => 1]]));
        $this->expectException(InsuffecientIngredientsException::class);
        $order->prepare();
    }
}
