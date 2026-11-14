<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // idとHelpers/const/rolesConst.php内のidが対応している必要がある。
        Role::create([
            'name' => 'SystemAdmin',
            'alias_name' => 'システム管理者',
            'description' => 'システム全体の変更権限を持つ',
            'type' => 'system'
        ]);

        Role::create([
            'name' => 'ServiceUser',
            'alias_name' => 'サービスユーザー',
            'description' => 'サンプルサービスユーザー',
            'type' => 'service'
        ]);
    }
}
