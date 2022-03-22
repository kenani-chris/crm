<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class Question extends Model
{
    use Uuids;

    protected $fillable=[
        'channel_id',
        'question_text',
        'priority'
    ];
}
