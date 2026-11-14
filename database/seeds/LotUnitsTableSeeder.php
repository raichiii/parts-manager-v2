<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lot_units')->insert([
            ['name' => '個'],
            ['name' => '袋'],
            ['name' => 'リール'],
            ['name' => 'パック'],
            ['name' => 'シート'],
            ['name' => '箱'],
            ['name' => '式'],
            ['name' => '本'],
            ['name' => 'レール'],
            ['name' => '巻'],
            ['name' => '台'],
            ['name' => '束'],
            ['name' => 'ロット'],
        ]);
    }
}
