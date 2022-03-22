<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class CommentSummary extends Model
{
    use Uuids;

    protected $fillable=[
        'comment_type_id',
       'comment_summary',
    ];
}
