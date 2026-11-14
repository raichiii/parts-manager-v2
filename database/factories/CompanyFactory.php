<?php

namespace Database\Factories;

use App\Models\Address\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'furigana' => $this->faker->name,
            'english_name' => $this->faker->word,
            'short_name' => $this->faker->name,
            'short_english_name' => $this->faker->word,
            'urls' => array($this->faker->url),
            'note' => $this->faker->sentence,
        ];
    }
}
