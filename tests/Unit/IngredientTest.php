<?php

namespace Tests\Unit;

use App\Exceptions\HighDemandException;
use App\Mail\IngrediantRunningLow;
use App\Models\Ingredient;
use Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_consume()
    {
        // avoid sending the below 50% email
        Mail::fake();

        $ingredient = Ingredient::factory()->create(['stock' => 150]);
        $ingredient->consume(100);

        $this->assertEquals(50, $ingredient->stock);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredient->id, 'stock' => 50]);
    }

    public function test_it_sends_warning_email_when_below_50_percent_for_first_time()
    {
        Mail::fake();

        $ingredient = Ingredient::factory()->create(['stock' => 150, 'recommended_stock' => 200]);
        $ingredient->consume(100);

        Mail::assertQueued(IngrediantRunningLow::class);
    }

    public function test_it_doesnt_send_email_when_original_stock_below_50_percent()
    {
        Mail::fake();

        $ingredient = Ingredient::factory()->create(['stock' => 90, 'recommended_stock' => 200]);
        $ingredient->consume(40);

        Mail::assertNotQueued(IngrediantRunningLow::class);
    }

    public function test_it_fails_to_consume_without_getting_lock()
    {
        Mail::fake();

        $this->expectException(HighDemandException::class);

        $ingredient = Ingredient::factory()->create(['stock' => 90, 'recommended_stock' => 200]);

        $lock = Cache::lock("ingredient_{$ingredient->id}", 6)->get();
        $ingredient->consume(40);
        $lock->release();
    }
}
