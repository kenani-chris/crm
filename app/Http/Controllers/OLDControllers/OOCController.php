<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GreaterOOCImport;
use App\Imports\LessOOCImport;
use App\Imports\PaymentImport;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\OOCGreater;
use App\OOCLess;
use App\Payment;
use Auth;

class OOCController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Index
     */
     public function index(){

         return view('ooc.index');
     }

     /**
      * Upload leads
      */

      public function uploadLeads(Request $request){



             if($request->channel==1){
                 //OOC > 50%
                 $saveExcel=Excel::import(new GreaterOOCImport, $request->file('file')->store('temp'));
             }else if($request->channel==2){
                //OOC < 50%
                $saveExcel=Excel::import(new LessOOCImport, $request->file('file')->store('temp'));
             }
             else if($request->channel==3){
                //OOC < 50%
                $saveExcel=Excel::import(new PaymentImport, $request->file('file')->store('temp'));
             }
             
             else{

             }

             if($saveExcel){
                $arr = array('msg' => 'Leads upload successfully', 'status' => true);
            }else{
                $arr = array('msg' => 'Something went wrong. Leads not uploaded!', 'status' => false);
            }
    
            return Response()->json($arr); 



      }

      /**
       * Display OCC Lead
       */

       public function displayLeads(Request $request, $id, $name){



        if($id==1){
            $name="OOC Greater than 50%";
        }else if($id==2){
            $name="OOC Less than 50%";
        }else{
            $name="";
        }
        return view('ooc.leads',['name'=>$name]);

       }

       /**
        * Greater OOC
        */

       public function displayOOCLeads(Request $request){

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value']; 

        if($request->xpath==1){
            $fromModel=new OOCGreater();

        }else if($request->xpath==2){
            $fromModel=new OOCLess();
            
        }else{
            $fromModel="";
        }


        $totalRecords= $fromModel::where('lastDisposition','like','%'. $searchValue.'%')
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('created_at', '>=',$request->date_from)->whereDate('created_at', '<=',$request->date_to);
        })
        ->count();

            //Start
            $totalRecordswithFilter=$totalRecords;
    
            $records= $fromModel::orderBy($columnName,$columnSortOrder)
            ->where('lastDisposition','like','%'. $searchValue.'%')
            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                    $q->whereDate('created_at', '>=',$request->date_from)->whereDate('created_at', '<=',$request->date_to);
             })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        if(!empty($fromModel)){

    
            $data_arr = array();
            $t=0;
            

            foreach($records as $record){
                $id = $record->id;
                $UnitSerialNumber = $record->UnitSerialNumber;
                $CustomerName = $record->CustomerName;
                $CustomerPhoneNumber= $record->CustomerPhoneNumber;
                $CustomerPhoneNumberAlternative= $record->CustomerPhoneNumberAlternative;
                $Financier= $record->Financier;
                $lastDisposition= $record->lastDisposition;
                $upload_date = $record->created_at;
                $t+=1;
        
                //$action='/catalogs/'.$record->id.'/catalog';

                $action="";
    
                
                $data_arr[] = array(
                  "id" =>'&nbsp;&nbsp;'.$t,
                  "UnitSerialNumber" => $UnitSerialNumber,
                  "CustomerName" => $CustomerName,
                  "CustomerPhoneNumber" =>$CustomerPhoneNumber,
                  "CustomerPhoneNumberAlternative" =>$CustomerPhoneNumberAlternative,
                  "Financier" => $Financier,
                  "lastDisposition" => $lastDisposition,
                  'updated_at'=>date('Y-m-d H:i:s', strtotime($record->updated_at)),
                  "created_at" =>  date('Y-m-d H:i:s', strtotime($upload_date)),
                  "action"=>$action
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
            //End


        }else{

        }

          



       }






}
