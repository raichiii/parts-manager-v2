<?php

namespace Database\Factories;

use App\User;
use App\Models\Address\Company;
use App\Models\Order\Job;
use App\Models\Board\Thing;
use App\Models\Order\OrderedList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderedListFactory extends Factory
{
    protected $model = OrderedList::class;

    public function definition(): array
    {
        print($this->faker->date());
        return [
            'job_id' => Job::inRandomOrder()->first()->id,
            'thing_id' => Thing::inRandomOrder()->first()->id,
            'lot_size' => $this->faker->numberBetween($min = 1, $max = 1000),
            'lot_unit_id' => $this->faker->numberBetween($min = 1, $max = 4),
            'lot_amount' => $this->faker->numberBetween($min = 1, $max = 10),
            'supplier_id' => Company::inRandomOrder()->first()->id,
            'unit_price' => $this->faker->numberBetween($min = 100, $max = 100000),
            'depository' => Str::random(3),
            'requested_user_id' => User::inRandomOrder()->first()->id,
            'ordered_user_id' => User::inRandomOrder()->first()->id,
            'requested_at' => $this->faker->date(),
        ];
    }
}
