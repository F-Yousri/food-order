<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public function consume(int $weight): void
    {
        // acquire a redis lock to avoid overlapping
        // consume the weight
        $this->stock -= $weight;
        $this->save();
    }
}
