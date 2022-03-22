<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;



class ConnectToStreamingAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'connect_to_streaming_api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect to the Twitter Streaming API';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {


        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
       
    }
}
