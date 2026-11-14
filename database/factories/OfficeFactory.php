<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address\Company;
use App\Models\Address\Office;

/**
 * @extends Factory<Model>
 */
class OfficeFactory extends Factory
{
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'name' => $this->faker->city(),
            'prefecture_id' => rand(1,47),
            'address' => $this->faker->unique()->address(),
            'phones' => array($this->faker->unique()->phoneNumber()),
            'emails' => array($this->faker->unique()->safeEmail()),
            'faxes' => array(),
        ];
    }
}
