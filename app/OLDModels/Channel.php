<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Channel extends Model
{
    use Uuids;

    protected $fillable=[
        'title',
        'isactive'
    ];



    public function channel_menu()
    {
      //return $this->belongsTo('App\Menu', 'parent_id');
    }
}
