<?php

use Illuminate\Database\Seeder;
use App\Disposition;
use App\DispositionTypes;
use Illuminate\Support\Str;

class DispositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reachables = array(
            'Complete Call',
            'Call Back',
            'Customer Hung Up',
            'Not Interested',
            'Not Contact Owner',
            'Silent Call'
        );


        $not_reachables = array(
            'No Answer',
            'Phone Switched Off',
            'Call Drop',
            'Phone Busy',
            'Number Out Of Service',
            'Number Incorrect',
            'Voice Mail',
        );

        //Reachable
        $disposition_type_reachable =DispositionTypes::firstWhere('title', 'Reachable')->id;

        foreach($reachables as $reachable){
            Disposition::Create([
                'title'=>$reachable,
                'slug'=>Str::slug($reachable),
                'disposition_type_id'=> $disposition_type_reachable,
            ]);
        }

        //Unreachable

        $disposition_type_un_reachable =DispositionTypes::firstWhere('title', 'Not Reachable')->id;


        foreach($not_reachables  as $not_reachable){
            Disposition::Create([
                'title'=>$not_reachable,
                'slug'=>Str::slug($not_reachable),
                'disposition_type_id'=> $disposition_type_un_reachable,
            ]);
        }








    }
}
