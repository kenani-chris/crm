<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class BodyShopSurvey extends Model
{
    use Uuids;
    
    protected $fillable=[
       'intro',
        'q1',
        'q2',
        'callback',
        'q3',
       'q3_comments',
       'q4_comment_type_id',
       'q4_channel_id',
       'q4_comment_summary_id',
        'q4_action_required',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
       'q9_comments',
       'q10',
       'q10_yes_comments',
       'q11_comment_type_id', 
       'q11_channel_id',
       'q11_comment_summary_id',
        'q11_action_required', 
        'q12',
       'disposition_id',
       'bodyshop_lead_id',
        'lastQuestion',
       'user_id',
    ];

    public function leads(){

        return $this->belongsTo('App\BodyShopLeads','bodyshop_lead_id','id');
        
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
