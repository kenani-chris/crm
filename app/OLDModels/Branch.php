<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class Branch extends Model
{
    use Uuids;
    
    protected $fillable=[
        'title',
        'code',
        'isactive'
    ];
}
