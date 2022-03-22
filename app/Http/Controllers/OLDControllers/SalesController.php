<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesLeads;
use App\SalesSurvey;
use App\DispositionTypes;
use App\Disposition;
use App\Channel;
use Auth;
use Carbon\Carbon;


class SalesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

      /**
       * Introduction
       */
      public function intro(Request $request, $id){


        $this->validate($request, [
            'callStatus' => 'required|exists:disposition_types,id',
        ]);



        $survey_type="Sales";

        $channel= $survey_type." Channel ";

        $modelName='App\\'.$survey_type.'Leads';
    
        $leadClass=new  $modelName;

        $customer=$leadClass::where('id',$id)->first();

        if($customer){

            $this->saveStart($request->callStatus, $id, $survey_type);

            $dispo_type=DispositionTypes::findOrFail($request->callStatus);

    
            $slug=strtolower($survey_type);

        if($dispo_type->slug=="not-reachable"){

            return view('outbound.dispositions.unreachable',['channel'=>$channel,'customer'=>$customer, 'slug'=>$slug]);

        }else if($dispo_type->slug=="reachable"){

           return $this-> introQuestion('q1', $id, $survey_type);

        }
    }
            else {

              return $this->$this->getIntro($survey_type);
            }
    
    }


    /**
     * Get Intro
     */
    public function getIntro($survey_type){

    

        $channel=$survey_type." Channel";

        $modelName='App\\'.$survey_type.'Leads';
    
        $leadClass=new  $modelName;

        $slug=strtolower($survey_type);

        $get_view="outbound.".$slug.'.index';
    
        $customer=$leadClass::inRandomOrder()->where('lastDisposition','Pending')->first();

        $callBack_id=Disposition::firstWhere('slug','call-back')->id;

        $unreachable_id=DispositionTypes::firstWhere('slug','not-reachable')->id;

        $sortDirection = 'desc';
    
        $customer_callback=$leadClass::inRandomOrder()->where('lastDisposition', $callBack_id)
        ->with(['survey'])
        ->whereHas('survey',function ($query) use($sortDirection,$callBack_id) {
            $query->where('user_id', Auth::user()->id)->where('disposition_id',$callBack_id)->where('callback','<=', date('Y-m-d H:i:s'))->orderBy('created_at', $sortDirection);
        })
        ->first();

        
        $call_unreachables=$leadClass::inRandomOrder()
        ->where('updated_at','<=', Carbon::now()->subHours(2)->toDateTimeString())
        ->with(['disposition'])
        ->whereHas('disposition', function($query) use ($unreachable_id){
          $query->where('disposition_type_id', $unreachable_id);
        })
        ->where('attempts','<',12)
        ->with(['survey'])
        ->whereHas('survey',function ($query) use($sortDirection) {
            $query->where('user_id', Auth::user()->id)->orderBy('created_at', $sortDirection);
        })
        ->first();

       
        return view($get_view,['channel'=> $channel,'customer'=>$customer, 'slug'=>$slug, 'customer_callback'=>$customer_callback, 'call_unreachables'=>$call_unreachables]);
    }


    /**
     * Intro Question
     */
    public function introQuestion($question, $id,  $survey_type){

        $channel= $survey_type." Channel ";

        $modelName='App\\'.$survey_type.'Leads';
    
        $leadClass=new  $modelName;

        $customer=$leadClass::where('id',$id)->first();

        $slug=strtolower($survey_type);

        $get_view="outbound.".$slug.'.questions';

        return view($get_view.'.'.$question,['channel'=>$channel,'customer'=>$customer,'slug'=>$slug]);
    }


    /**
     * Save Survey Start
     */

    public function saveStart($intro, $lead_id, $survey_type){

        $modelName='App\\'.$survey_type.'Survey';
    
        $surveyClass=new  $modelName;

        $leadModel='App\\'.$survey_type.'Leads';

        $leadClass=new $leadModel;


        $slug=strtolower($survey_type);

        $survey_lead_id=$slug.'_lead_id';

        $checkDuplicate=$surveyClass::where($survey_lead_id, $lead_id)->whereNull('disposition_id')->orderBy('created_at','DESC')->get(['id','lastQuestion']);

        if($checkDuplicate->count()==0){

        $class_data= $surveyClass;
        $class_data->intro=$intro;
        $class_data->$survey_lead_id=$lead_id;
        $class_data->lastQuestion="intro";
        $class_data->user_id=Auth::user()->id;
        if($class_data->save())
        {
            $this->changeLeadDisposition($lead_id, 'In Progress', $survey_type);
        }
         }
    else {

        //Go to the lasr question
        $channel=$survey_type." Channel ";

        $customer=$leadClass::where('id',$lead_id)->first();

        if($checkDuplicate[0]->lastQuestion=='intro'){

        }else{
            $get_view="outbound.".$slug.'.questions';


        return view($get_view.'.'.$checkDuplicate[0]->lastQuestion,['channel'=>$channel,'customer'=>$customer, 'slug'=>$slug]);
        }
    }

    }


    /*
        Change Lead disposition
    */

    public function changeLeadDisposition($lead_id, $disposition, $survey_type){

        $leadModel='App\\'.ucwords($survey_type).'Leads';

        $leadClass=new $leadModel;

        $attempts= $leadClass->attempts+1;

        $lead=$leadClass::where('id',$lead_id)->update(array(
            'attempts'=> $attempts,
            'lastDisposition' =>$disposition
        ));
        return true;

    }


    /**
     * Sales Survey Questions
     */
    public function surveyQuestions(Request $request, $question, $id){

        $survey_type="Sales";

        $channel=$survey_type." Channel ";

        $leadModel='App\\'.$survey_type.'Leads';

        $leadClass=new $leadModel;

        $modelName='App\\'.$survey_type.'Survey';
    
        $surveyClass=new  $modelName;


        $customer=$leadClass::where('id',$id)->first();

        $slug=strtolower($survey_type);

        $survey_lead_id=$slug.'_lead_id';

        $getLastSurvey=$surveyClass::where($survey_lead_id,$id)->orderBy('created_at', 'DESC')->first();

        $survey_id=$getLastSurvey->id;

        switch($question){
            case 'q2':

                $updateSurvey=array('q1'=>$request->q1,'lastQuestion'=>$question);
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $request->q1=="Yes" ? $question='q2' : $question='q13'; //GO to Q10 if No
                break;
            case 'q3':
                 $updateSurvey=array('q2'=>$request->q2, 'callback'=>$request->callback_time,'lastQuestion'=>$question);
                 $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                 $request->q2=="Yes" ? $question='q3' : $question='q13';
                break;
            case 'q4':
                $updateSurvey=array(
                    'q3'=>$request->q3,
                    'q3_comments'=>$request->q3_comments,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);

                $question='q4';

                break;
            case 'q5':
                $updateSurvey=array(
                    'q4'=>$request->q4,
                    'q4_yes_comments'=>$request->q4_comments,
                    'lastQuestion'=>$question,
                );

                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);

                $question='q5';
                
                break;

            case 'q6':

                $updateSurvey=array(
                    'q5_comment_type_id'=>$request->q5_comment_type_id,
                    'q5_channel_id'=>$request->q5_channel_id,
                    'q5_comment_summary_id'=>$request->q5_comment_summary_id,
                    'q5_action_required'=>$request->q5_action_required,
                    'lastQuestion'=>$question,
                );
                /*$updateSurvey=array(
                    'q5'=>$request->q5,
                    'lastQuestion'=>$question,
                );*/
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $question='q6';
                break;

            case 'q7':
                    $updateSurvey=array(
                        'q6'=>$request->q6,
                        'lastQuestion'=>$question,
                    );
                    $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                    $question='q7';
                    break;
             case 'q8':
                    $updateSurvey=array(
                        'q7'=>$request->q7,
                        'lastQuestion'=>$question,
                    );
                    $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                    $question='q8';
                    break;
            case 'q9':
                $updateSurvey=array(
                    'q8'=>$request->q8,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $question='q9';
                break;

            case 'q10':
                $updateSurvey=array(
                    'q9'=>$request->q9,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $question='q10';
                break;
            case 'q11':
                $updateSurvey=array(
                    'q10'=>$request->q10,
                    'q10_comments'=>$request->q10_comments,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $question='q11';
                break;
            case 'q12':
                $updateSurvey=array(
                    'q11_comment_type_id'=>$request->q11_comment_type_id,
                    'q11_channel_id'=>$request->q11_channel_id,
                    'q11_comment_summary_id'=>$request->q11_comment_summary_id,
                    'q11_action_required'=>$request->q11_action_required,
                    'lastQuestion'=>$question,
                );

                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);

                $question='q12';
                break;
        
            case 'q13':
                    $updateSurvey=array(
                        'disposition_id'=>$request->disposition,
                        'lastQuestion'=>$question
                    );
                    if($this->saveSurvey($updateSurvey,$survey_id,$survey_type)){

                        $this->changeLeadDisposition($customer->id, $request->disposition,$survey_type);
                    }
                 return $this->getIntro($survey_type);
                 break;
            
            default:

                return;
        }

        $get_view="outbound.".$slug.'.questions';

        return view($get_view.'.'.$question,['channel'=>$channel,'customer'=>$customer, 'slug'=>$slug]);
    }


    /**
     * Save Survey
     */

    public function saveSurvey($payload,$survey_id, $survey_type){

        $modelName='App\\'.$survey_type.'Survey';
    
        $surveyClass=new  $modelName;

        $surveyClass::where('id',$survey_id)->whereNotNull('intro')->update($payload);

        return true;

    }

    /**
     * 
     * Terminate Survey
     */
    public function terminateSurvey(Request $request, $lead_id){


        $this->validate($request, [
            "channel"    => "required",
            'disposition' => 'required'
        ]);

        $channel_type=ucwords($request->channel);
        if($channel_type=="bodyshop"){
            $channel_survey="Body Shop";
        }else{
            $channel_survey= $channel_type;
        }

       
        $get_channel=Channel::where('title',$channel_survey)->first();

        if( $get_channel->count()>0){

            $survey_type=str_replace(' ', '',$request->channel);

            $modelName='App\\'.ucwords($survey_type).'Survey';
    
            $surveyClass=new  $modelName;

            $slug=strtolower($survey_type);

            $survey_lead_id=$slug.'_lead_id';

            $getLastSurvey=$surveyClass::where($survey_lead_id,$lead_id)->orderBy('created_at', 'DESC')->first();

            if($getLastSurvey->count()>0){

                $updateSurvey=array(
                    'disposition_id'=>$request->disposition
                );

                $survey_id=$getLastSurvey->id;
            
                $save=$getLastSurvey->update($updateSurvey);

                if($save){
                    $this->changeLeadDisposition($lead_id,$request->disposition,$survey_type);

                    $arr = array('msg' =>url('/channel/'.$get_channel->id.'/intro/'), 'status' => true);

                }else{
                    $arr = array('msg' => 'Something went wrong. Please try again!', 'status' => false);
                }
            }
            else{
                $arr = array('msg' => 'Survey records not available. Please try again!', 'status' => false);
            }

    

        }else{
            $arr = array('msg' => 'Unkown channel. Please contact your system adminstrator!', 'status' => false);
        }

        return Response()->json($arr);
     }
  

}
