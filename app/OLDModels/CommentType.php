<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class CommentType extends Model
{
    use Uuids;

    protected $fillable=[
        'title'
    ];
}
