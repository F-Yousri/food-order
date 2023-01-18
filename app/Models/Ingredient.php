<?php

namespace App\Models;

use App\Mail\IngrediantRunningLow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Ingredient extends Model
{
    use HasFactory;

    public function consume(int $weight): void
    {
        // acquire a redis lock to avoid overlapping
        // consume the weight
        $originalStock = $this->stock;
        $this->stock -= $weight;
        $this->save();

        if($originalStock > ($this->recommended_stock / 2) && $this->stock <= ($this->recommended_stock / 2)) {
            Mail::to(config('mail.merchant_email'))
                ->queue(new IngrediantRunningLow($this->name));
        }
        
    }
}
