<?php

namespace App\Providers;
use App\MailHandler;
use BeyondCode\Mailbox\Facades\Mailbox;

use Illuminate\Support\ServiceProvider;
use Phirehose;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      
    return; //dun

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Mailbox::catchAll(function(InboundEmail $email) {
            // Work with incoming email   
            app('log')->debug($email);

        });*/

        Mailbox::to('incoming@codelific.com', MailHandler::class);
    }
}
