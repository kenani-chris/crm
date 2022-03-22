<?php

use Illuminate\Database\Seeder;
use App\Traits\Uuids;
use App\DispositionTypes;
use Illuminate\Support\Str;


class DispositionTypeTableSeeder extends Seeder
{

    use Uuids;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // types of dispositions
        $types = array(
            'Reachable',
            'Not Reachable'
        );

        foreach($types as $type){
            DispositionTypes::Create([
                'title'=>$type,
                'slug' =>Str::slug($type)
            ]);
        }

    }
}
