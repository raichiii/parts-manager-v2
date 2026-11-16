<?php

namespace Database\Factories;

use App\Models\Address\Office;
use App\Models\Address\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name;
        $furigana = get_furigana($name);
        return [
            'office_id' => Office::inRandomOrder()->first()->id,
            'name' => $name,
            'furigana' => $furigana,
        ];
    }
}
