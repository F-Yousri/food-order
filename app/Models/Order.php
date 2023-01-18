<?php

namespace App\Models;

use App\Enums\OrderStatus;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'orders_products')->withPivot(['quantity']);
    }

    /**
     * 
     * 
     * @throws \Throwable
     */
    public function prepare()
    {
        // A transaction to ensure all is prepared or nothing
        try {
            DB::transaction(function() {
                $this->products->each(fn ($product) => $product->prepare($product->pivot->quantity));
                $this->status = OrderStatus::Completed;
                $this->save();
            });
        } catch (\Throwable $th) {
            $this->status = OrderStatus::Failed;
            throw $th;
        }
    }
}
