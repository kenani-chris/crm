<?php

use Illuminate\Database\Seeder;
use App\Channel;
use App\Traits\Uuids;


class ChannelTableSeeder extends Seeder
{

    use Uuids;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = array(
            'Sales',
            'Parts',
            'Body Shop',
            'Service',
        );

        foreach($channels  as $channel){
            Channel::Create([
                'title'=>$channel
            ]);
        }

    }
}
