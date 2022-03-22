<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class OOCLessSurvey extends Model
{
    use Uuids;

    protected $fillable=[
        'intro',
       'q1',
       'q2',
       'q3',
       'q3_time',
       'q3_date',
       'q4',
       'q4_others',
       'q5',
       'q5_others',
       'q6',
       'q6_others',
       'q7',
       'q7_others',
       'q8',
       'q8_others',
       'q9',
       'q10',
       'q11',
       'q12',
       'disposition',
       'lastQuestion',
        'o_o_c_greater_id',
        'user_id'
     ];

     public function lead(){

        return $this->belongsTo('App\OOCLess', 'lead_id','id');
    }

    public function agent(){
        return $this->hasMany('App\User','id','user_id');
    }
}
