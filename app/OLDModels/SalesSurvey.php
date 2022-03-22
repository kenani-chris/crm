<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class SalesSurvey extends Model
{
    use Uuids;

    protected $fillable=[
        'intro',
        'q1', //
        'q2', //
        'callback', //
        'q3', //
        'q3_comments', //
        'q4',
        'q4_yes_comments',
        'q5_comment_type_id', //q5
        'q5_channel_id', //q5
        'q5_comment_summary_id', //q5
        'q5_action_required', //q5
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'q10_comments',
        'q11_comment_type_id', //q10
        'q11_channel_id', //q11
        'q11_comment_summary_id', //q11
        'q11_action_required', //q11
        'q12',
        'disposition_id',
        'sales_lead_id',
        'lastQuestion',
        'user_id',
    ];


    public function leads(){

        return $this->belongsTo('App\SalesLeads','sales_lead_id','id');
        
    }

    public function callstatus(){
        return $this->belongsTo('App\DispositionTypes', 'intro','id');
    }

    public function disposition(){
        return $this->belongsTo('App\Disposition', 'disposition_id','id');
    }

    public function q5commenttype(){
        return $this->belongsTo('App\CommentType', 'q5_comment_type_id','id');
    }
    public function q5channel(){
        return $this->belongsTo('App\Channel', 'q5_channel_id','id');     
    }
    public function q5commentsummary(){
        return $this->belongsTo('App\CommentSummary', 'q5_comment_summary_id','id');
    }

    public function q11commenttype(){
        return $this->belongsTo('App\CommentType', 'q11_comment_type_id','id');
    }
    public function q11channel(){
        return $this->belongsTo('App\Channel', 'q11_channel_id','id');     
    }
    public function q11commentsummary(){
        return $this->belongsTo('App\CommentSummary', 'q11_comment_summary_id','id');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}

