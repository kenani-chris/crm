<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\OOCGreater;
use App\OOCLess;
use App\OOCGreaterSurvey;
use App\OOCLessSurvey;

use Auth;


class OOCGreaterSurveyController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($question){

        
    }


    public function intro(Request $request, $id){

        $this->validate($request, [
            'callStatus' => 'required|in:Reachable,Unreachable',
        ]);

        $channel="OOC Greater than 50% Channel";

        $customer=OOCGreater::where('id',$id)->first();

        if($customer){

            $this->saveStart($request->callStatus, $id);

        if($request->callStatus=="Unreachable"){
            return view('outbound.dispositions.unreachable',['channel'=>$channel,'customer'=>$customer]);
        }else if($request->callStatus=="Reachable"){

            return $this-> introQuestion('q1', $id);

        }
    }
            else {
               return $this->$this->getIntro();
            }
    
    }

    public function introQuestion($question, $id){

        $channel="OOC Greater than 50% Channel";

        $customer=OOCGreater::where('id',$id)->first();

        return view('outbound.oocgreater.questions.'.$question,['channel'=>$channel,'customer'=>$customer]);
    }



    public function surveyQuestions(Request $request, $question, $id){

        $channel="OOC Greater than 50% Channel";

        $customer=OOCGreater::where('id',$id)->first();

        $getLastSurvey=OOCGreaterSurvey::where('lead_id',$id)->orderBy('created_at', 'DESC')->first();

        $survey_id=$getLastSurvey->id;

        switch($question){
            case 'q2':

                $updateSurvey=array('q1'=>$request->q1,'lastQuestion'=>$question);
                $this->saveSurvey($updateSurvey,$survey_id);

                $request->q1=="Yes" ? $question='q3' : $question='q2';

                break;
            case 'q3':
                 $updateSurvey=array('q2'=>$request->q2,'lastQuestion'=>$question);
                 $this->saveSurvey($updateSurvey,$survey_id);
                 $request->q2=="Related to the Customer" ||  $request->q2=="Not Related to the Customer" ? $question='q12' : $question='q12';
                break;
            case 'q4':
                $updateSurvey=array(
                    'q3'=>$request->q3,
                    'q3_time'=>$request->q3_time,
                    'q3_date'=>$request->q3_date,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id);
                $this-> updateFollowup($customer->id, $request->q3_time, $request->q3_date);
                $request->q3=='Today' ? $question='q10' : $question='q4';
                break;
            case 'q5':
                $currentquiz=$request->q4;

                $updateSurvey=array(
                    'q4'=>$currentquiz,
                    'q4_others'=>$request->q4_others,
                    'lastQuestion'=>$question,
                );

                $this->saveSurvey($updateSurvey,$survey_id);
                
                if($currentquiz=='Financial Constraints'){
                    $question='q5';
                }else if($currentquiz=='Agent Related Issues'){
                    $question='q6';
                }else if($currentquiz=='Product Faulty'){
                    $question='q7';    
                }else if($currentquiz=='Missing Components'){
                    $question='q8';
                }else if($currentquiz=='Using Alternative Light Source'){
                    $question='q9';  
                }else if($currentquiz=='Others'){
                    $question='q10';
                }else{

                }
                break;
            case 'q10':
                if($request->has('q5')){
                    $updateSurvey=array(
                        'q5'=>$request->q5,
                        'q5_others'=>$request->q5_others,
                        'lastQuestion'=>$question
                    );
                } else if($request->has('q6')){
                    $updateSurvey=array(
                        'q6'=>$request->q6,
                        'q6_others'=>$request->q6_others,
                        'lastQuestion'=>$question
                    );
                }
                else if($request->has('q7')){
                    $updateSurvey=array(
                        'q7'=>$request->q7,
                        'q7_others'=>$request->q7_others,
                        'lastQuestion'=>$question
                    );
                }
                else if($request->has('q8')){
                    $updateSurvey=array(
                        'q8'=>$request->q8,
                        'q8_others'=>$request->q8_others,
                        'lastQuestion'=>$question
                    );
                }else{}
                    $this->saveSurvey($updateSurvey,$survey_id);
                break;
            case 'q11':
                
                break;
            case 'q12':
                $updateSurvey=array(
                    'q11'=>$request->q11,
                    'lastQuestion'=>$question,
                );
                $this->saveSurvey($updateSurvey,$survey_id);
                break;
            case 'q13':
                    $updateSurvey=array(
                        'q12'=>$request->q12,
                        'disposition'=>$request->disposition,
                        'lastQuestion'=>$question
                    );
                    if($this->saveSurvey($updateSurvey,$survey_id)){
                        $this->changeLeadDisposition($customer->id, $request->disposition);
                    }
                 return $this->getIntro();
            case 'q14':
                $updateSurvey=array(
                    'disposition'=>$request->disposition,
                    'lastQuestion'=>$question
                );

                if($this->saveSurvey($updateSurvey,$survey_id)){
                    $this->changeLeadDisposition($customer->id, $request->disposition);
               }
                return $this->getIntro();
                break;
            default:

                return;
        }


        return view('outbound.oocgreater.questions.'.$question,['channel'=>$channel,'customer'=>$customer]);
    }

   
  /**
   * Satart survey
   */
    public function saveStart($intro, $lead_id){

        $checkDuplicate=OOCGreaterSurvey::where('lead_id', $lead_id)->whereNull('disposition')->orderBy('created_at','DESC')->get(['id','lastQuestion']);


      //  dd($checkDuplicate[0]->lastQuestion);

        if($checkDuplicate->count()==0){

        $oocgreater=new OOCGreaterSurvey();
        $oocgreater->intro=$intro;
        $oocgreater->lead_id=$lead_id;
        $oocgreater->lastQuestion="intro";
        $oocgreater->user_id=Auth::user()->id;
        if($oocgreater->save())
        {
            $this->changeLeadDisposition($lead_id, 'In Progress');
        }
         }
    else {

        //Go to the lasr question
        $channel="OOC Greater than 50% Channel";
        $customer=OOCGreater::where('id',$lead_id)->first();
        if($checkDuplicate[0]->lastQuestion=='intro'){

        }else{
        return view('outbound.oocgreater.questions.'.$checkDuplicate[0]->lastQuestion,['channel'=>$channel,'customer'=>$customer]);
        }
    }

    }

    

    /**
     * Terminate Survey
     */

     public function terminateSurvey(Request $request, $lead_id){


        $this->validate($request, [
            "channel"    => "required|in:1,2",
            'disposition' => 'required'
        ]);

            if($request->channel==1){
                    $toModel=new OOCGreaterSurvey();
                    //$redirect=route('survey.intro');
            }else if($request->channel==2){
                $toModel=new OOCLessSurvey();

                $redirect="";
            }else{

            }

             $getLastSurvey=$toModel::where('lead_id',$lead_id)->orderBy('created_at', 'DESC')->first();

            if($getLastSurvey->count()>0){

                $updateSurvey=array(
                    'disposition'=>$request->disposition
                );

                $survey_id=$getLastSurvey->id;
            
                $save=$getLastSurvey->update($updateSurvey);

                if($save){
                    $this->changeLeadDisposition($lead_id,$request->disposition);
                    $arr = array('msg' =>url('/channel/'.$request->channel.'/intro/'), 'status' => true);
                }else{
                    $arr = array('msg' => 'Something went wrong. Please try again!', 'status' => false);
                }
            }
            else{
                $arr = array('msg' => 'Survey records not available. Please try again!', 'status' => false);
            }

        return Response()->json($arr);
     }

     /**
      * Redirect with
      */
     public function redirectWith($channel){

        if($channel==1){
            $toModel=new OOCGreater();
        
        }else if($channel==2){
            $toModel=new OOCLess();
        }else{
            return redirect()->route('join.channel');
        }


        $customer=$toModel::inRandomOrder()->where('lastDisposition','Pending')->first();

        if($channel==1){

            $channel="OOC Greater than 50% Channel";
        }
        else{
           
            $channel="OOC Less than 50% Channel";
        }

        return redirect()->route('channel.intro',$channel)->with( ['customer' =>$customer,'channel'=>$channel] );

     }


     public function getIntro(){

        $channel="OOC Greater than 50% Channel";
    
        $customer=OOCGreater::inRandomOrder()->where('lastDisposition','Pending')->first();
    
        return view('outbound.oocgreater.index',['channel'=> $channel,'customer'=>$customer]);
    }


    public function changeLeadDisposition($lead_id, $disposition){

        $lead=OOCGreater::where('id',$lead_id)->update(array(
            'lastDisposition' =>$disposition
        ));
        return true;

    }

    public function saveSurvey($payload,$survey_id){

        $oocgreater=OOCGreaterSurvey::where('id',$survey_id)->whereNotNull('intro')->update($payload);

        return true;

    }



    /**
     * 
     */
     public function updateFollowup($lead_id, $follow_time, $follow_date){

        $todayDate=date('Y-m-d').' '.$follow_time;

        if(!empty($follow_time)){
            $follow_time=$todayDate;
        }

        if(!empty($follow_date)){
            $follow_time=date('Y-m-d', strtotime($follow_date));
            $follow_date=date('Y-m-d', strtotime($follow_date));
        }

        $lead=OOCGreater::find($lead_id);
        $lead->update(array(
            'no_of_followups'=>0,
            'payment_promise_time'=>$follow_time,
            'payment_promise_date'=>$follow_date ,
        ));
        return true;

     }


}


