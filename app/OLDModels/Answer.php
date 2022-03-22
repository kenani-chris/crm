<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Answer extends Model
{
    use Uuids;

    protected $fillbale=[
        'question_id',
        'answer',
        'real_answer',
        'redirect_to',
        'has_text_box',
        'has_date_picker'
    ];
}
