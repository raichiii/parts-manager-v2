<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 開発用のユーザー
        $new_user = User::create([
            'username' => 'admin',
            'name' => '管理者',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);
        $new_user->roles()->attach(SYSTEM_ADMIN);
        $new_user->roles()->attach(SERVICE_USER);

        $new_user = User::create([
            'username' => 'yamada',
            'name' => '山田孝之',
            'email' => 'tyamada@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = User::create([
            'username' => 'abe',
            'name' => '阿部寛',
            'email' => 'habe@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = User::create([
            'username' => 'flect',
            'name' => '株式会社フレクト',
            'email' => 'flect@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = User::create([
            'username' => 'unknown',
            'name' => '不明',
            'email' => 'unknown@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(10)->create();
    }
}
