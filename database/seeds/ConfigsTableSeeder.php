<?php

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = Config::create([
            'key' => 'job_path',
            'value' => '',
        ]);
        $config = Config::create([
            'key' => 'job_r_path',
            'value' => 'job量産/',
        ]);
        $config = Config::create([
            'key' => 'part_search_address',
            'value' => 'https://www.google.com/search?hl=ja&source=hp&biw=&bih=&q=[part]',
        ]);
        $config = Config::create([
            'key' => 'detailed_board_search_address',
            'value' => 'https://www.google.com/search?hl=ja&source=hp&biw=&bih=&q=[part]',
        ]);
    }
}
