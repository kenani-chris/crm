<?php

use Illuminate\Database\Seeder;

class EmailPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\EmailPost::class, 5)->create();
    }
}
