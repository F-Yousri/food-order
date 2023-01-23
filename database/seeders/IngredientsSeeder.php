<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{

    public function run()
    {
        Ingredient::insert([
            ['name' => 'Beef', 'stock' => 20000, 'recommended_stock' => 20000,],
            ['name' => 'Cheese', 'stock' => 5000, 'recommended_stock' => 5000,],
            ['name' => 'Onion', 'stock' => 1000, 'recommended_stock' => 1000,],
        ]);
    }
}
