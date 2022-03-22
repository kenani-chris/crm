<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;
use App\DispositionTypes;
use App\CommentType;
use App\Channel;
use App\CommentSummary;
use App\Disposition;


class ServiceLeads extends Model
{

    use Uuids;
    
    protected $fillable=[
       'DateOut',
       'CustomerName',
       'MakeModel',
       'Registration',
       'CompanyName',
       'MobileNumber',
       'ServiceAdvisor',
       'BranchCode1',
       'lastDisposition',
       'callback',
        'attempts',
       'user_id'
    ];

    

    protected $appends = ['disposition_types','survey_channels','comment_types','comment_summary','complete_call','reachable_dispositions','unreachable_dispositions'];


    public function branch(){
        return $this->hasMany('App\Branch','code','BranchCode1');
    }

    public function getDispositionTypesAttribute($value)
        {
            return DispositionTypes::where('isactive',1)->get();
        }

        public function getCommentTypesAttribute($value)
        {
            return CommentType::all();
        }

        public function getSurveyChannelsAttribute($value)
        {
            return Channel::all();
        }

        public function getCommentSummaryAttribute($value){
            return CommentSummary::all();
        }

        public function getCompleteCallAttribute($value){
            return Disposition::where('slug','complete-call')->orWhere('slug','not-interested')->orWhere('slug','call-back')->get();
        }

        public function getReachableDispositionsAttribute($value){

            $disposition_type_reachable =DispositionTypes::firstWhere('title', 'Reachable')->id;

            return Disposition::where('disposition_type_id', $disposition_type_reachable)->get();
        }

        public function getUnreachableDispositionsAttribute($value){

            $disposition_type_reachable =DispositionTypes::firstWhere('title', 'Not Reachable')->id;

            return Disposition::where('disposition_type_id', $disposition_type_reachable)->get();
        }

        public function survey(){
            return $this->hasMany('App\ServiceSurvey','service_lead_id','id');
        }

        public function disposition(){

            return $this->belongsTo('App\Disposition','lastDisposition','id');
            
        }

}


