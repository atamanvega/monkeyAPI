<?php

use App\Customer;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Customer::truncate();
        User::truncate();

        Customer::flushEventListeners();
        User::flushEventListeners();

        factory(User::class, 1)->create();
    }
}
