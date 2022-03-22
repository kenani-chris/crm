<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      //$this->call(UserTableSeeder::class);
       // $this->call(EmailPostTableSeeder::class);
       //$this->call(ChannelTableSeeder::class);
       //$this->call(DispositionTypeTableSeeder::class);
       //$this->call(DispositionTableSeeder::class);

       //$this->call(ServicesScriptTableSeeder::class);
       //$this->call(BranchTableSeeder::class);
       //$this->call(CommentTypesTableSeeder::class);
       $this->call(CommentSummaryTableSeeder::class);

    }
}
