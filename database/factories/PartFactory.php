<?php

namespace Database\Factories;

use App\Models\Board\Thing;
use App\Models\Board\Part;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    protected $model = Part::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $thing = Thing::factory()->create([
            "type" => "Part",
        ]);
        return [
            'thing_id' => $thing->id,
            'name' => Str::random($this->faker->numberBetween($min = 8, $max = 16)),
        ];
    }
}
