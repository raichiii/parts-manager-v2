<?php

namespace Database\Factories;

use App\Models\Board\Thing;
use App\Models\Address\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Thing>
 */
class ThingFactory extends Factory
{
    protected $model = Thing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Part', 'PrintCircuitBoard', 'Other']),
            'model_no' => Str::random($this->faker->numberBetween($min = 16, $max = 24)),
            'maker_id' => Company::inRandomOrder()->first()->id,
        ];
    }
}
