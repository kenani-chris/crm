<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\OOCGreater;
use App\OOCLess;
use App\OOCGreaterSurvey;
use App\OOCLessSurvey;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\OOCLessFollowup;
use App\OOCGreaterFollowup;

use Auth;


class FollowupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('outbound.followup');
    }


    public function followUpChannel(Request $request, $channel){

        if($request->isMethod('post')){
    
        $this->validate($request, [
          'channel' => 'required|numeric|in:1,2',
        ]);
    
        $reqchannel=$request->channel;
        
      }else{
        $reqchannel=$channel;
      }
    
        if($reqchannel==1){
    
          $channel="OOC Greater than 50% Channel";
    
          $customer=OOCGreater::inRandomOrder()->where('lastDisposition','Pending')->first();
    
        
            return view('outbound.oocgreater.followup.index',['channel'=> $channel,'customer'=>$customer]);
    
        }else if( $reqchannel==2){
    
          $channel="OOC Less than 50% Channel";
    
            $customer=OOCLess::inRandomOrder()->where('lastDisposition','Pending')->first();
    
            return view('outbound.oocless.followup.index',['channel'=> $channel,'customer'=>$customer]);
    
        }else{
    
            return view('outbound.followup');
    
        }
    
      }
      /**
       * 
       * Followups
       */

       public function todayFollowups(Request $request){

        

        if($request->channel==1){
            $toModel=new OOCGreaterSurvey();
        }else if($request->channel==2){
            $toModel=new OOCLessSurvey();
        }else {
            $toModel=new OOCGreaterSurvey();
        }

        $todayDate=date('Y-m-d H:i');




        

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $disposition1="Commit To Pay";
        $disposition="To pay Later";


        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value']; 
        $totalRecords=$toModel::where('disposition',$disposition)->with(['lead'])
        ->whereHas('lead', function($query) use($todayDate) {
          $query->whereDate('payment_promise_time','<=',date('Y-m-d', strtotime($todayDate)))->whereTime('payment_promise_time','<=',date('H:i', strtotime($todayDate))); //->orWhereDate('payment_promise_date','<=',date('Y-m-d', strtotime($todayDate)));
        })
        ->orWhere('disposition',$disposition1)
        ->count();

        $totalRecordswithFilter=$totalRecords;

        $records=$toModel::orderBy($columnName,$columnSortOrder)
        ->with(['lead'])
        ->whereHas('lead', function($query) use($todayDate) {
          $query->whereDate('payment_promise_time','<=',date('Y-m-d', strtotime($todayDate)))->whereTime('payment_promise_time','<=',date('H:i', strtotime($todayDate))); //->orWhereDate('payment_promise_date','<=',date('Y-m-d', strtotime($todayDate)));          //->whereDate('payment_promise_date','<=',date('Y-m-d', strtotime($todayDate)));
        })
        ->where('disposition',$disposition1)
        ->orWhere('disposition',$disposition)
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();

        $t=0;

        foreach($records as $record){
            $id = $record->id;

            $t+=1;

            if(strtotime($record->payment_promise_time) <= strtotime($todayDate))
            {
              $action='<button class="btn btn-primary btn-sm" data-toggle="modal" id="followupBtn" data-target="#followupModal" data-id='.$record->id.'><i class="mdi mdi-pencil"></i> Follow up</button>';
            }
            else{
              $action="";
            }

            
            $promise_dtime='';

              $promise_dtime=$record->lead->payment_promise_time;
            
            $data_arr[] = array(
              "id" =>'&nbsp;&nbsp;'.$t,
              "UnitSerialNumber" => $record->lead->UnitSerialNumber,
              "CustomerName" => $record->lead->CustomerName,
              "CustomerPhoneNumber" => $record->lead->CustomerPhoneNumber,
              "no_of_followups" =>$record->lead->no_of_followups,
              "promise_date_time" =>  $promise_dtime,
              "updated_at" =>date('Y-m-d H:i:s', strtotime($record->lead->updated_at)),
              "action"=>$action,
             
            );
         }

         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
         );

         echo json_encode($response);
         exit;

       }


       /**
        * Get lead
        */

        public function getLead($id,$channel){

          if($channel==1)
          {
            $toModel=new OOCGreaterSurvey();

          }else if($channel==2){
             $toModel=new OOCLessSurvey();

          }else{

          }
         
          if($toModel){

            $results=$toModel::where('id', $id)->with(['lead'])->get();

            if($results->count()>0){

              $amountToPay=number_format(($results[0]->lead->PaymentPlanOutstandingBalance)-($results[0]->lead->EWallet));

              $arr = array('msg' =>$results,'amount'=> $amountToPay, 'status' => true);

            }else{
              $arr = array('msg' => 'No record found. Please try again!', 'status' => false);
            }


          }else{
            $arr = array('msg' => 'Something went wrong. No details found!', 'status' => false);
          }

           return Response()->json($arr); 
        
        }

        /**
         * Confirm Followup
         */

         public function confirmFollowup(Request $request, $channel){

          $this->validate($request, [
            'q3' => 'required|in:Later,Today,Paid',
            'x_id' => 'required',
          ]);

          if($channel==1)
          {
            $toModel=new OOCGreaterSurvey();
            $toFollowup=new OOCGreaterFollowup();
          }else if($channel==2){
             $toModel=new OOCLessSurvey();
             $toFollowup=new OOCLessFollowup();
          }else{

          }

          if($toModel){

            $results=$toModel::find($request->x_id);

            if($results->count()>0){

              //Save Followup
              $toFollowup->survey_id=$request->x_id;
              $toFollowup->user_id=Auth::user()->id;
              $toFollowup->lead_id=$results->lead_id;
              $toFollowup->followup_status=$request->q3;
              $toFollowup->next_follow_up_time=$request->q3_time;
              $toFollowup->next_follow_up_date=$request->q3_date;

              if($request->q3=="Later"){
                $lastDisposition="To pay Later";
              }
              else if($request->q3=="Today"){
                $lastDisposition='Commit To Pay';
              }else if($request->q3=="Paid"){
                $lastDisposition='Paid';
              }else{
                $lastDisposition=$results->disposition;
              }
             

              if($toFollowup->save()){
               
                $this->updateFollowup($results->lead_id, $request->q3_time,$request->q3_date, $channel,$lastDisposition);
                $arr = array('msg' =>"Followup update successfull", 'status' => true);
              
              }else{
                $arr = array('msg' => 'Followup status not updated. Please try again!', 'status' => false);
              }
              //End Followup

            }else{
              $arr = array('msg' => 'No record found. Please try again!', 'status' => false);
            }


          }else{
            $arr = array('msg' => 'Something went wrong. No details found!', 'status' => false);
          }

           return Response()->json($arr); 
    
  
         }



           /**
     * 
     */
     public function updateFollowup($lead_id, $follow_time, $follow_date, $channel,$lastDisposition){

      $todayDate=date('Y-m-d').' '.$follow_time;

      if(!empty($follow_time)){
          $follow_time=$todayDate;
      }

      if(!empty($follow_date)){
          $follow_time=date('Y-m-d', strtotime($follow_date));
          $follow_date=date('Y-m-d', strtotime($follow_date));
      }


      if($channel==1)
          {
            $toModel=new OOCGreater();
          }else if($channel==2){
             $toModel=new OOCLess();
          }else{

          }

      $lead=$toModel::find($lead_id);
      $lead->update(array(
          'no_of_followups'=>$lead->no_of_followups+1,
          'payment_promise_time'=>$follow_time,
          'payment_promise_date'=>$follow_date ,
          'lastDisposition'=>$lastDisposition,
      ));
      return true;

   }


         /**
          * Create folluwp
          */

}
