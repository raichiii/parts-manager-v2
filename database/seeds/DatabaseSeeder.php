<?php

use App\Models\Order\RequestedList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Redis::flushDB();
        $this->call(RolesTableSeeder::class);
        // ロールを持たせたテストユーザーを作成しているためRolesTableSeederの後に実行
        $this->call(UsersTableSeeder::class);
        $this->call(PrefecturesSeeder::class);
        $this->call(ClassificationsTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(IndividualsTableSeeder::class);
        $this->call(JobsTableSeeder::class);
        $this->call(OfficesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(PrintCircuitBoardsTableSeeder::class);
        // $this->call(DetailedBoardsTableSeeder::class);
        $this->call(PartsTableSeeder::class);
        $this->call(LotUnitsTableSeeder::class);
        $this->call(RequestedListsTableSeeder::class);
        $this->call(OrderedListsTableSeeder::class);
        $this->call(PurchasedListsTableSeeder::class);
        $this->call(ConfigsTableSeeder::class);
    }
}
