<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $new_user = App\User::create([
            'username' => 'admin',
            'name' => '管理者',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);
        $new_user->roles()->attach(SYSTEM_ADMIN);
        $new_user->roles()->attach(SERVICE_USER);

        $new_user = App\User::create([
            'username' => 'flect',
            'name' => '株式会社フレクト',
            'email' => 'flect@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = App\User::create([
            'username' => 'unknown',
            'name' => '不明',
            'email' => 'unknown@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = App\User::create([
            'username' => 'raichi',
            'name' => 'Raichi',
            'email' => 'raichi@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = App\User::create([
            'username' => 'cherii',
            'name' => 'Cherii',
            'email' => 'cherii@test.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        $new_user = App\User::create([
            'username' => 'softbank',
            'name' => 'ソフトバンク',
            'email' => 'test@softbank.com',
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);
    }
}
