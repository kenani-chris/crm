<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\EmailPost::class, function(Faker $generator) {
    return [
        'title' => $generator->sentence(),
        'body'=> $generator->text(1000)
    ];
});
