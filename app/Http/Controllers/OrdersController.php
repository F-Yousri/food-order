<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;

class OrdersController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->status = OrderStatus::Preparing;
        $order->save();

        $products = $request->validated()['products'];
        $order->products()->sync(collect($products)
            ->mapWithKeys(
                fn ($product) => [$product['product_id'] => ['quantity' => $product['quantity']]]));

        $order->prepare();

        return response()->json([], 204);
    }
}
