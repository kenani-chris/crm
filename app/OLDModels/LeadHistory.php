<?php

namespace App;

use App\Traits\Uuids;


use Illuminate\Database\Eloquent\Model;

class LeadHistory extends Model
{
    use Uuids;

    protected $fillable=[
        'lead_id',
        'user_id',
        'disposition',
        'lead_category',
    ];

}
