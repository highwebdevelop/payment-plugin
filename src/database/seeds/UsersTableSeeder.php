<?php

namespace Payment\System\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table("users")->insertOrIgnore([
            'email' => 'new@gmail.com',
            'password' => Hash::make('1111'),
        ]);
    }
}
