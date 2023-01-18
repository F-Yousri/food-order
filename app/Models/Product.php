<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'products_ingredients')->withPivot(['weight']);
    }

    public function prepare()
    {
        // TODO: must be done in a transaction to ensure all is consumed or nothing
        $this->ingredients->each(fn ($ingredient) => $ingredient->consume($ingredient->pivot->weight));
    }
}
