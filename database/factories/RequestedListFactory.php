<?php

namespace Database\Factories;

use App\Models\Order\RequestedList;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestedListFactory extends Factory
{
    protected $model = RequestedList::class;

    public function definition(): array
    {
        return [
            'job_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'model_no' => $this->faker->regexify('[A-Z]{4}-[0-9]{3}'),
            // 'supplier_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'lot_size' => $this->faker->numberBetween($min = 1, $max = 1000),
            'lot_unit_id' => $this->faker->numberBetween($min = 1, $max = 4),
            'lot_amount' => $this->faker->numberBetween($min = 1, $max = 10),
            'requested_user_id' => $this->faker->numberBetween($min = 1, $max = 3),
        ];
    }
}
