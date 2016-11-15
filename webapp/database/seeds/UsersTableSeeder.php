<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->where('email', '=', 'admin@relay-control.com')->first();

        if (!$user) {
            DB::table('users')->insert([
                'name'       => 'Administrator',
                'email'      => 'admin@relay-control.com',
                'password'   => bcrypt('Admin123!$'),
                'role_id'       => 1,
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ]);
        }
    }
}
