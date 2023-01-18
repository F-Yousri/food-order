<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('weight')->comment('weight in grams');

            $table->foreignId('product_id')->index()->constrained();
            $table->foreignId('ingredient_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_ingredients');
    }
};
