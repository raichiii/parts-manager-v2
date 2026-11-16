<?php

namespace Database\Factories;

use App\Models\Address\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company;
        $furigana = get_furigana($name);
        $english_name = $this->faker->word;
        return [
            'name' => $name,
            'furigana' => $furigana,
            'english_name' => $english_name,
            'short_name' => Str::remove(['株式', '有限', '会社', ' '], $name),
            'short_english_name' => Str::substr($english_name, 0, 4),
            'urls' => array($this->faker->url),
            'note' => $this->faker->sentence,
        ];
    }
}
