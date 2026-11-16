<?php

namespace Database\Factories;

use App\Models\Board\Thing;
use App\Models\Board\PrintCircuitBoard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PrintCircuitBoard>
 */
class PrintCircuitBoardFactory extends Factory
{
    protected $model = PrintCircuitBoard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $thing = Thing::factory()->create([
            "type" => "PrintCircuitBoard",
        ]);
        return [
            'thing_id' => $thing->id,
            'valid_years' => $this->faker->numberBetween($min = 1, $max = 10),
            'description' => $this->faker->sentence(5),
        ];
    }
}
