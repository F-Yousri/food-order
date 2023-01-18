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

    /**
     * @throws Illuminate\Contracts\Cache\LockTimeoutException
     */
    public function prepare(int $quantity = 1)
    {
        $this->ingredients->each(fn ($ingredient) => $ingredient->consume($quantity * $ingredient->pivot->weight));
    }
}
