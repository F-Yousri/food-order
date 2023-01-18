<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Exception;

class OrdersController extends Controller
{

    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->status = OrderStatus::Preparing;
        $order->save();
        // attach products
        
        try {
            $order->prepare();
        } catch (\Illuminate\Contracts\Cache\LockTimeoutException $e) {
            return response()->json([
                'message' => "We're experiencing exceptionally high demand. Please try again later."
            ], 503, ['Retry-After' => 10]);
        } 
        // catch () {

        // } 
        catch (Exception $e) {
            return response()->json([
                'message' => 'There was an issue while processing your. Please try again later. If the issue persists, please contact us.'
            ], 500);
        }
        
    }
}
