<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class ServiceSurvey extends Model
{

    use Uuids;

    protected $fillable=[
        'intro',
        'q1',
        'q2',
        'callback',
        'q3',
        'q3_comments',
        'q4_comment_type_id', //q4
        'q4_channel_id', //q4
        'q4_comment_summary_id', //q4
        'q4_action_required', //q4
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q9_comments',
        'q10_comment_type_id', //q10
        'q10_channel_id', //q10
        'q10_comment_summary_id', //q10
        'q10_action_required', //q10
        'q11',
        'disposition_id',
        'service_lead_id',
        'lastQuestion',
        'user_id'
    ];
    public function leads(){

        return $this->belongsTo('App\ServiceLeads','service_lead_id','id');
        
    }

    public function callstatus(){
        return $this->belongsTo('App\DispositionTypes', 'intro','id');
    }

    public function disposition(){
        return $this->belongsTo('App\Disposition', 'disposition_id','id');
    }

    public function q4commenttype(){
        return $this->belongsTo('App\CommentType', 'q4_comment_type_id','id');
    }
    public function q4channel(){
        return $this->belongsTo('App\Channel', 'q4_channel_id','id');     
    }
    public function q4commentsummary(){
        return $this->belongsTo('App\CommentSummary', 'q4_comment_summary_id','id');
    }

    public function q10commenttype(){
        return $this->belongsTo('App\CommentType', 'q10_comment_type_id','id');
    }
    public function q10channel(){
        return $this->belongsTo('App\Channel', 'q10_channel_id','id');     
    }
    public function q10commentsummary(){
        return $this->belongsTo('App\CommentSummary', 'q10_comment_summary_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
