<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => bcrypt('user@user.com'),
        ]);
        Account::insert([
            'user_id' => 1,
            'totalamount' => 0,
            'addedamount' => 0,
            'pendingamount' => 0,
            'deductedamount' => 0,
        ]);
    }
}
