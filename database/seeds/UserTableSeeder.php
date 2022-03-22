<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'name' => 'Admin DTDobie',
            'level' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('secret')
        ]);

    

    }
}
