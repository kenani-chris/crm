<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\User;
use App\Channel;
use App\Disposition;
use App\DispositionTypes;

use Auth;
use App\Http\Controllers\UserController;
use DB;

class ReportsController extends Controller
{
    
    protected $dtdobieAgents;
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserController $dtdobieAgents)
    {
        $this->middleware('auth');

        $this->UserController=$dtdobieAgents;
    }

    public function index($id){

        $survey_type=Channel::findOrFail($id);

        $channel_name=str_replace(' ', '',$survey_type->title);

        $slug=strtolower($channel_name);

        $disposition=Disposition::all();

        $disposition_type=DispositionTypes::all();
    
        if($survey_type->count()>0){


            $channel=$survey_type->title.' Channel';

            $agents=$this->UserController->listAgents();

            return view('reports.index',['channel'=>$channel,'agents'=>$agents, 'slug'=>$slug,'disposition_type'=>$disposition_type,'disposition'=>$disposition]);

        }else{
            return view('reports.index',['channel'=>"Service",'agents'=>$agents, 'slug'=>$slug,'disposition_type'=>$disposition_type,'disposition'=>$disposition]);
        }
       
    }


    public function rawDataReport(Request $request){


        $survey_type=Channel::findOrFail($request->xpath);


        $survey_channel=str_replace(' ', '',$survey_type->title);

        $modelName='App\\'.$survey_channel.'Survey';

        $surveyClass=new  $modelName;

        $draw = $request->draw;
        $start = $request->start;
        $rowperpage = $request->length;

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;

        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        $totalRecords=$surveyClass::when($request->has('callStatus'), function($q) use($request) {
            $q->where('intro', 'like','%'.$request->callStatus.'%');
        })
        ->when($request->has('callDisposition'), function($q) use($request) {
            $q->where('disposition_id', 'like','%'.$request->callDisposition.'%');
        })
        ->when($request->has('agent'), function($q) use($request) {
            $q->where('user_id', 'like','%'.$request->agent.'%');
        })
        ->when((!empty($request->completion_from) && !empty($request->completion_to)), function($q) use($request){
            $q->whereDate('created_at', '>=',$request->completion_from)->whereDate('created_at', '<=',$request->completion_to);
        })->with(['user'])->with(['leads'])
        ->whereHas('leads', function($query) use ($request) {
            $query->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request) {
                $q->whereDate('created_at', '>=',$request->date_from)->whereDate('created_at', '<=',$request->date_to);
            });
        })
        ->count();

        $totalRecordswithFilter=$totalRecords;

    
        $records=$surveyClass::orderBy($columnName,$columnSortOrder)
                        ->when($request->has('callStatus'), function($q) use($request) {
                            $q->where('intro', 'like','%'.$request->callStatus.'%');
                        })
                       
                        ->when($request->has('callDisposition'), function($q) use($request) {
                            $q->where('disposition_id', 'like','%'.$request->callDisposition.'%');
                        })
                        ->when($request->has('agent'), function($q) use($request) {
                            $q->where('user_id', 'like','%'.$request->agent.'%');
                        })
                        ->when((!empty($request->completion_from) && !empty($request->completion_to)), function($q) use($request){
                            $q->whereDate('created_at', '>=',$request->completion_from)->whereDate('created_at', '<=',$request->completion_to);
                        })
                    ->with(['user'])
                    ->with(['leads'])
                    ->whereHas('leads', function($query) use ($request) {
                        $query->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request) {
                            $q->whereDate('created_at', '>=',$request->date_from)->whereDate('created_at', '<=',$request->date_to);
                        });
                    })
                    ->select('*',DB::raw('(CASE 
                                            WHEN q3>=0 AND q3<=6 THEN "Detractor" 
                                            WHEN q3>=7 AND q3<=8 THEN "Neutral" 
                                            WHEN q3>=9 AND q3<=10 THEN "Promoter" 
                                            ELSE "" 
                                            END) AS q3_type'))
                    ->with(['callstatus'])
                    ->skip($start)
                            ->take($rowperpage)
                            ->get();

        $data_arr = array();

        $slug=strtolower($survey_type->title);

        $methodTo=str_replace(' ', '',$slug).'Report';



        $this->$methodTo($records, $data_arr, $draw, $totalRecords, $totalRecordswithFilter);

       

    }



  /**
   * Sales Reports
   */

   public function salesReport($records, $data_arr, $draw, $totalRecords, $totalRecordswithFilter){
   
    $t=0;
    foreach($records as $record){
        $t+=1;

        if(isset($record->disposition->title)){
            $disposition=$record->disposition->title;
        }else{
            $disposition="In Progress";
        }
        $data_arr[] =array(
            "id" =>'&nbsp;&nbsp;'.$t,
            'DistributorNameOutletName'=> $record->leads->DistributorNameOutletName,
            'RetailOutletDealerCode'=> $record->leads->RetailOutletDealerCode,
            'Title'=> $record->leads->Title,
            'Initials'=> $record->leads->Initials,
            'Surname'=> $record->leads->Surname,
            'Landline'=> $record->leads->Landline,
            'Mobile'=> $record->leads->Mobile,
            'TransactionType'=> $record->leads->TransactionType,
            'CompanyName'=> $record->leads->CompanyName,
            'FleetGovernmentPrivate'=> $record->leads->FleetGovernmentPrivate,
            'ModelCode'=> $record->leads->ModelCode,
            'ModelName'=> $record->leads->ModelName,
            'RegistrationVIN'=> $record->leads->RegistrationVIN,
            'TransactionDate'=> $record->leads->TransactionDate,
            'SalesPersonName'=> $record->leads->SalesPersonName,
            'intro'=> $record->callstatus->title,
            'q1'=>$record->q1, 
            'q2'=>$record->q2,
            'callback'=>$record->callback, 
            'q3'=>$record->q3, 
            'type'=>$record->q3_type,
            'q3_comments'=>$record->q3_comments, 
            'q4'=>$record->q4,
            'q4_yes_comments'=>$record->q4_yes_comments,
            'q5_comment_type_id'=>isset($record->q5commenttype->title) ? $record->q5commenttype->title : '',
            'q5_channel_id'=>isset($record->q5channel->title) ? $record->q5channel->title :'',
            'q5_comment_summary_id'=>isset($record->q5commentsummary->comment_summary) ? explode('-',$record->q5commentsummary->comment_summary)[0] :'',
            'q5_action_required'=>$record->q5_action_required,
            'q6'=>$record->q6,
            'q7'=>$record->q7,
            'q8'=>$record->q8,
            'q9'=>$record->q9,
            'q10'=>$record->q10,
            'q10_comments'=>$record->q10_comments,
            'q11_comment_type_id'=>isset($record->q11commenttype->title) ? $record->q11commenttype->title : '',
            'q11_channel_id'=>isset($record->q11channel->title) ? $record->q11channel->title :'', 
            'q11_comment_summary_id'=>isset($record->q11commentsummary->comment_summary) ? explode('-',$record->q11commentsummary->comment_summary)[0] :'',
            'q11_action_required' => $record->q11_action_required, 
            'user_id'=>$record->user->name,
            'disposition_id'=>$disposition,
            'created_at'=>date('Y-m-d', strtotime($record->created_at)),
            'created_at_time'=>date('H:i:s', strtotime($record->created_at))
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

   /**end sales report
    */

    /**
     * Service Reports
     */
    public function serviceReport($records, $data_arr, $draw, $totalRecords, $totalRecordswithFilter){

    
   
        $t=0;
        foreach($records as $record){
            $t+=1;
    
            if(isset($record->disposition->title)){
                $disposition=$record->disposition->title;
            }else{
                $disposition="In Progress";
            }
            $data_arr[] =array(
                "id" =>'&nbsp;&nbsp;'.$t,
                'DateOut'=>$record->leads->DateOut,
                'CustomerName'=>$record->leads->CustomerName,
                'MakeModel'=>$record->leads->MakeModel,
                'Registration'=>$record->leads->Registration,
                'CompanyName'=>$record->leads->CompanyName,
                'MobileNumber'=>$record->leads->MobileNumber,
                'ServiceAdvisor'=>$record->leads->ServiceAdvisor,
                'BranchCode1'=>$record->leads->BranchCode1,
                'intro'=> $record->callstatus->title,
                'q1'=>$record->q1, 
                'q2'=>$record->q2,
                'callback'=>$record->callback, 
                'q3'=>$record->q3, 
                'type'=>$record->q3_type,
                'q3_comments'=>$record->q3_comments, 
                'q4_comment_type_id'=>isset($record->q4commenttype->title) ? $record->q4commenttype->title : '',
                'q4_channel_id'=>isset($record->q4channel->title) ? $record->q4channel->title :'',
                'q4_comment_summary_id'=>isset($record->q4commentsummary->comment_summary) ? explode('-',$record->q4commentsummary->comment_summary)[0] :'',
                'q4_action_required'=>$record->q4_action_required,
                'q5'=>$record->q5,
                'q6'=>$record->q6,
                'q7'=>$record->q7,
                'q8'=>$record->q8,
                'q9'=>$record->q9,
                'q9_comments'=>$record->q9_comments,
                'q10_comment_type_id'=>isset($record->q10commenttype->title) ? $record->q10commenttype->title : '',
                'q10_channel_id'=>isset($record->q10channel->title) ? $record->q10channel->title :'', 
                'q10_comment_summary_id'=>isset($record->q10commentsummary->comment_summary) ? explode('-',$record->q10commentsummary->comment_summary)[0] :'',
                'q10_action_required' => $record->q10_action_required,
                'user_id'=>$record->user->name,
                'disposition_id'=>$disposition,
                'created_at'=>date('Y-m-d', strtotime($record->created_at)),
                'created_at_time'=>date('H:i:s', strtotime($record->created_at))
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
     * End Service Reports
     */

     /**
      * Body Shop
      */
      public function bodyshopReport($records, $data_arr, $draw, $totalRecords, $totalRecordswithFilter){

    
   
        $t=0;
        foreach($records as $record){
            $t+=1;
    
            if(isset($record->disposition->title)){
                $disposition=$record->disposition->title;
            }else{
                $disposition="In Progress";
            }

            $data_arr[] =array(
                "id" =>'&nbsp;&nbsp;'.$t,
                'DateOut'=>$record->leads->DateOut,
                'CustomerName'=>$record->leads->CustomerName,
                'MakeModel'=>$record->leads->MakeModel,
                'Registration'=>$record->leads->Registration,
                'CompanyName'=>$record->leads->CompanyName,
                'MobileNumber'=>$record->leads->MobileNumber,
                'ServiceAdvisor'=>$record->leads->ServiceAdvisor,
                'BranchCode1'=>$record->leads->BranchCode1,
                'intro'=> $record->callstatus->title,
                'q1'=>$record->q1, 
                'q2'=>$record->q2,
                'callback'=>$record->callback, 
                'q3'=>$record->q3, 
                'type'=>$record->q3_type,
                'q3_comments'=>$record->q3_comments, 
                'q4_comment_type_id'=>isset($record->q4commenttype->title) ? $record->q4commenttype->title : '',
                'q4_channel_id'=>isset($record->q4channel->title) ? $record->q4channel->title :'',
                'q4_comment_summary_id'=>isset($record->q4commentsummary->comment_summary) ? explode('-',$record->q4commentsummary->comment_summary)[0] :'',
                'q4_action_required'=>$record->q4_action_required,
                'q5'=>$record->q5,
                'q6'=>$record->q6,
                'q7'=>$record->q7,
                'q8'=>$record->q8,
                'q9'=>$record->q9,
                'q9_comments'=>$record->q9_comments,
                'q10'=>$record->q10,
                'q10_yes_comments'=>$record->q10_yes_comments,
                'q11_comment_type_id'=>isset($record->q11commenttype->title) ? $record->q11commenttype->title : '',
                'q11_channel_id'=>isset($record->q11channel->title) ? $record->q11channel->title :'', 
                'q11_comment_summary_id'=>isset($record->q11commentsummary->comment_summary) ? explode('-',$record->q11commentsummary->comment_summary)[0] :'',
                'q11_action_required' => $record->q11_action_required,
                'user_id'=>$record->user->name,
                'disposition_id'=>$disposition,
                'created_at'=>date('Y-m-d', strtotime($record->created_at)),
                'created_at_time'=>date('H:i:s', strtotime($record->created_at))
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
       * End Body Shop
       */



    

}
