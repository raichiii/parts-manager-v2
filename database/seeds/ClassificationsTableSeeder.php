<?php

use Illuminate\Database\Seeder;
use App\Models\Classification;

class ClassificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classification::create([
            'name' => 'requester',
            'alias_name' => '依頼元',
            'type' => 'Company',
            'description' => '会社分類において依頼元かどうか',
        ]);

        Classification::create([
            'name' => 'subcontractor',
            'alias_name' => '外注先',
            'type' => 'Company',
            'description' => '会社分類において外注先かどうか',
        ]);

        Classification::create([
            'name' => 'supplier',
            'alias_name' => '仕入れ先',
            'type' => 'Company',
            'description' => '会社分類において仕入れ先かどうか',
        ]);

        Classification::create([
            'name' => 'maker',
            'alias_name' => 'メーカー',
            'type' => 'Company',
            'description' => '会社分類においてメーカーかどうか',
        ]);

        Classification::create([
            'name' => 'facility',
            'alias_name' => '設備・事務',
            'type' => 'Company',
            'description' => '会社分類において設備・事務かどうか',
        ]);

        Classification::create([
            'name' => 'government',
            'alias_name' => '官公庁',
            'type' => 'Company',
            'description' => '会社分類において官公庁かどうか',
        ]);

        Classification::create([
            'name' => 'self',
            'alias_name' => '個人',
            'type' => 'Company',
            'description' => '会社分類において個人会社',
        ]);

        Classification::create([
            'name' => 'other',
            'alias_name' => 'その他',
            'type' => 'Company',
            'description' => '会社分類においてその他に分類される',
        ]);
    }
}
