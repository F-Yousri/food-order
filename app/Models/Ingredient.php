<?php

namespace App\Models;

use App\Mail\IngrediantRunningLow;
use Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Ingredient extends Model
{
    use HasFactory;

    public function consume(int $weight): void
    {
        // acquire a distributed lock to avoid overlapping
        $lock = Cache::lock("ingredient_{$this->id}", 10);

        try {
            $lock->block(2);
         
            // consume the weight
            $originalStock = $this->stock;
            $this->stock -= $weight;
            $this->save();

            $warningLimit = $this->recommended_stock / 2;
            
            if($originalStock > $warningLimit && $this->stock <= $warningLimit) {
                Mail::to(config('mail.merchant_email'))
                    ->queue(new IngrediantRunningLow($this->name));
            }
        } catch (LockTimeoutException $e) {
            // retry or inform user we can't process order...
            throw $e;
        } finally {
            optional($lock)->release();
        }
      
        
    }
}
