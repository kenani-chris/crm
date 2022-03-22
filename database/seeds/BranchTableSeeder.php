<?php

use Illuminate\Database\Seeder;
use App\Branch;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = array(
            'Nairobi' => 101,
            'Mombasa' => 201,
            'Nakuru' => 301,
            'Kisumu' => 401
        );

        foreach ($branches as $branch => $code) {
            Branch::query()->create([
                'title' => $branch,
                'code' => $code
            ]);
        }

    }
}
