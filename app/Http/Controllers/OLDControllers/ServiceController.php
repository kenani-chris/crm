<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceLeads;
use App\ServiceSurvey;
use App\DispositionTypes;
use App\Channel;
use Auth;


class ServiceController extends Controller
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



        $survey_type="Service";

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
    
        return view($get_view,['channel'=> $channel,'customer'=>$customer, 'slug'=>$slug]);
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


        if($survey_type=="bodyshop"){
            $survey_type="BodyShop";
        }

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
     * Service Survey Questions
     */
    public function surveyQuestions(Request $request, $question, $id){

        $survey_type="Service";

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
                $request->q1=="Yes" ? $question='q2' : $question='q12'; //GO to Q10 if No
                break;
            case 'q3':
                 $updateSurvey=array('q2'=>$request->q2, 'callback'=>$request->callback_time,'lastQuestion'=>$question);
                 $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                 $request->q2=="Yes" ? $question='q3' : $question='q12';
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
                    'q4_comment_type_id'=>$request->q4_comment_type_id,
                    'q4_channel_id'=>$request->q4_channel_id,
                    'q4_comment_summary_id'=>$request->q4_comment_summary_id,
                    'q4_action_required'=>$request->q4_action_required,
                    'lastQuestion'=>$question,
                );

                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);

                $question='q5';
                
                break;

            case 'q6':

                $updateSurvey=array(
                    'q5'=>$request->q5,
                    'lastQuestion'=>$question,
                );
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
                    'q9_comments'=>$request->q9_comments,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);
                $question='q10';
                break;
            case 'q11':
                $updateSurvey=array(
                    'q10_comment_type_id'=>$request->q10_comment_type_id,
                    'q10_channel_id'=>$request->q10_channel_id,
                    'q10_comment_summary_id'=>$request->q10_comment_summary_id,
                    'q10_action_required'=>$request->q10_action_required,
                    'lastQuestion'=>$question,
                );

                $this->saveSurvey($updateSurvey,$survey_id, $survey_type);

                $question='q11';
                break;
        
            case 'q12':
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

    
        if($request->channel=="bodyshop"){
            $channel_survey="Body Shop";
        }else{
            $channel_survey= $channel_type;
        }


       
        $get_channel=Channel::where('title',$channel_survey)->first();

        if( $get_channel->count()>0){

            $survey_type=str_replace(' ', '',$request->channel);

            if($survey_type=="bodyshop"){
                $survey_type="BodyShop";
            }else{
                $survey_type=ucwords($survey_type);
            }

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
