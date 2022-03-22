<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class OOCLessFollowup extends Model
{
      use Uuids;
    protected $fillable=[
        'survey_id',
        'lead_id',
        'user_id',
        'followup_status',
        'next_follow_up_time',
        'next_follow_up_date',
    ];
}
