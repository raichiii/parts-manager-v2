<?php

namespace Database\Factories;

use App\Models\Order\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween($min = 10000, $max = 99999),
            'name' => $this->faker->sentence(rand(2,3)),
            'requester_id' => $this->faker->numberBetween($min = 1, $max = 99),
            'delivery_destination_id' => $this->faker->numberBetween($min = 1, $max = 99),
        ];
    }
}
