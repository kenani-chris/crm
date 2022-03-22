<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Member;
use App\Models\Report;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\ToyotaCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    public function index($id){

        $channel_name=str_replace(' ','',Campaign::findOrFail($id)->name);

        $branches=Branch::all();

        $brands=Brand::all();

        $advisors=User::with(['role'])->whereHas('role', function($q){
            $q->where('slug','champion')->orWhere('slug','advisor');
        })->get();

        return view('reports.index', get_defined_vars());
    }


   /**
    * Monthly Reports
    */  
    public function monthlyReports(Request $request, $id){

        $draw = $request->draw;
        $start = $request->start;
        $rowperpage = $request->length;

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;

        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }
        

        /*if($rowperpage=="-1"){
            $limit="";
        }else{
            $limit="LIMIT ".(int)$start.",".(int)$rowperpage."";
        }

        $order_limit="ORDER BY ".$columnName." ".$columnSortOrder." ".$limit."";
*/
        $totalRecords=Member::with([
                    'campaign.question',
                    'disposition.disposition_type',
                    'user',
                    'contact',
                    'schedule',
                    'report.question.answer',
                    'report.answer',
                    'report.branch',
                    'report.brand',
                    'report.classification',
                    'report.classification_type',
                    'toyota_case.campaign',
                    'toyota_case.voc_category',
                    'toyota_case.classification_type',
                    'toyota_case.classification'
        ])
        ->whereNotNull('disposition_id')
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
            })
        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
            $q->whereHas('brand', function($q) use ($request)  {
                $q->where('id','like','%'.$request->brand.'%');
            });
        })
        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
            if($branchRequestID == $westlandBranchID){
                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                    $q->whereHas('branch', function($query) use ($request)  {
                        $query->where('id','like','%'.$request->branch.'%');
                    });
                    $q->whereHas('brand', function($query) {
                        $query->where('slug','toyota');
                    });
                }else{
                    $q->whereHas('branch', function($q) {
                        $q->whereRaw("true = false");
                    });
                }
            }elseif($branchRequestID == $kisumuBranchID){
                if(empty($brandRequest)){ 
                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                        $query->where('id',$westlandBranchID);
                        $query->orWhere('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                        $query->Where('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                        $query->Where('id',$westlandBranchID);
                    });
                }
            }else{
                $q->whereHas('branch', function($q) use ($request)  {
                    $q->where('id','like','%'.$request->branch.'%');
                });
            }
        })
        ->where('campaign_id', $id)
        ->count();

        $totalRecordswithFilter=$totalRecords;

        $records=Member::orderBy($columnName,$columnSortOrder)
                            ->with([
                                'campaign.question',
                                'disposition.disposition_type',
                                'user',
                                'contact',
                                'schedule',
                                'report.question.answer',
                                'report.answer',
                                'report.branch',
                                'report.brand',
                                'report.classification',
                                'report.classification_type',
                                'toyota_case.campaign',
                                'toyota_case.voc_category',
                                'toyota_case.classification_type',
                                'toyota_case.classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                })
                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                $q->whereHas('brand', function($q) use ($request)  {
                                    $q->where('id','like','%'.$request->brand.'%');
                                });
                    
                            })
                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                if($branchRequestID == $westlandBranchID){
                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                        $q->whereHas('branch', function($query) use ($request)  {
                                            $query->where('id','like','%'.$request->branch.'%');
                                        });
                                        $q->whereHas('brand', function($query) {
                                            $query->where('slug','toyota');
                                        });
                                    }else{
                                        $q->whereHas('branch', function($q) {
                                            $q->whereRaw("true = false");
                                        });
                                    }
                                }elseif($branchRequestID == $kisumuBranchID){
                                    if(empty($brandRequest)){ 
                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                            $query->where('id',$westlandBranchID);
                                            $query->orWhere('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                            $query->Where('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                            $query->Where('id',$westlandBranchID);
                                        });
                                    }
                                }else{
                                    $q->whereHas('branch', function($q) use ($request)  {
                                        $q->where('id','like','%'.$request->branch.'%');
                                    });
                                }
                            })
                            ->whereNotNull('disposition_id')
                            ->where('campaign_id', $id)
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();
            $data_arr = array();
            $t=0;


         // return $records;

           

            foreach($records as $record){
                $t+=1;

                //return $record->report;

                $q=array();

               foreach($record->report()->whereNotIn('question_priority',[1,2,3,count($record->campaign->question)])->get() as $report){
                    if($report->question_priority == 5){
                        $q[$report->question_priority]=$report->text_box_answer;
                    }else{
                        $q[$report->question_priority]=isset($report->answer) ? ucfirst(\Illuminate\Support\Str::lower($report->answer->real_answer)) : '';
                    }
                }
               
            
                if(isset($q[4]) && ($q[4]>= 0 && $q[4] <= 6)){
                    $type='Detractor';
                } else if(isset($q[4]) && ($q[4] == 7 || $q[4] == 8)){
                    $type='Passive';
                } else if( isset($q[4]) && ($q[4] == 9 || $q[4] == 10)){
                    $type='Promoter';
                }else if(empty($q[4])) {
                    $type='';
                }else{
                    $type='';
                }

                // fixing the westland and kismu branch bug from backend because cant change the database
                $branch = '';
                if(isset($record->branch)){
                    if($record->brand->slug != 'toyota' && ($record->branch->slug == 'kisumu' || $record->branch->slug == 'westlands')){
                        $branch = 'Kisumu';
                    }elseif(isset($record->branch->name)){
                        $branch = $record->branch->name;
                    }else{
                        $branch = $record->branch_id;
                    }
                }
                
                if( isset($record->toyota_case) && (empty($record->toyota_case->comments) && ($record->toyota_case->classification_type->slug == "enquiries" || $record->toyota_case->classification_type->slug == "negative"))){
                    $is_closed = 'Open';
                }else{
                    $is_closed = 'Closed';
                }
                

                $data_arr[] =array(
                    "id" =>'&nbsp;&nbsp;'.$t,
                    'date_of_delivery' => $record->contact->date_of_delivery,
                    'customer'=>$record->contact->customer,
                    'customer_description'=>$record->contact->customer_description, //contact_person
                    'telephone_one'=>$record->contact->telephone_one,
                    'cust_classification'=>$record->contact->cust_classification,
                    'license_plate_number'=>$record->contact->license_plate_number,
                    'vin_number'=>$record->contact->vin_number,
                    'vehicle_model'=>$record->contact->vehicle_model,
                    'new_used_vehicle'=>$record->contact->new_used_vehicle,
                    'name'=>isset($record->contact->created_by) ? isset(\App\Models\User::query()->firstWhere('pf_no',$record->contact->created_by)->name) ? \App\Models\User::query()->firstWhere('pf_no',$record->contact->created_by)->name:'' : '',
                    'branch'=>$branch,
                    'brand' => isset($record->brand->name) ? $record->brand->name : $record->brand_id,
                    'reason_for_visit'=>$record->contact->reason_for_visit,
                    'order_number'=>$record->contact->order_number,
                    'header_text'=>$record->contact->header_text,
                    'gate_pass_ind'=>$record->contact->gate_pass_ind,
                    'campaign'=>$record->campaign->name,
                    'q4'=>isset($q[4]) ? $q[4] : '',
                    'q5'=>isset($q[5]) ? $q[5] : '', 
                    'q5c'=>$type,
                    'q6'=>isset($q[6]) ? $q[6] : '',
                    'q7'=>isset($q[7]) ? $q[7] : '',
                    'q8'=>isset($q[8]) ? $q[8] : '', 
                    'q9'=>isset($q[9]) ? $q[9] : '',
                    'q10'=>isset($q[10]) ? $q[10] : '',
                    'q11'=>isset($q[11]) ? $q[11] : '',
                    'voc_customer'=>isset($record->toyota_case) ? $record->toyota_case->voc_customer : '',
                    'classification_type'=>isset($record->toyota_case) ? $record->toyota_case->classification_type->name: '',
                    'campaign' =>isset($record->toyota_case) ? $record->toyota_case->campaign->name : '',
                    'classification'=>isset($record->toyota_case) ? $record->toyota_case->classification->name : '',
                    'action' => isset($record->toyota_case) ? ucfirst(\Illuminate\Support\Str::lower($record->toyota_case->action)) : '',
                    'is_closed'=>$is_closed,
                    'comments'=>(isset($record->toyota_case) && isset($record->toyota_case->comments)) ? $record->toyota_case->comments : '',
                    'updated_at'=>\Carbon\Carbon::parse($record->updated_at)->format('m/d/Y'),
                    'time' => \Carbon\Carbon::parse($record->updated_at)->format('h:i a'),
                    'username'=>isset($record->user) ? $record->user->name: '',
                    'hour'=>\Carbon\Carbon::parse($record->updated_at)->format('H'),
                    'disposition_type'=>$record->disposition->disposition_type->name ,
                    'disposition'=>$record->disposition->name,
                    'next_call_scheduled_at'=>$record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('m/d/Y') :'',
                    'next_call_scheduled_at_time'=>$record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('h:i a') :'',
                    'attempts'=> number_format($record->attempts),
                    'aware' => (isset($record->awareness_creation) && isset($record->awareness_creation->aware)) ? $record->awareness_creation->aware : '',
                    'satisfaction' => (isset($record->awareness_creation) && isset($record->awareness_creation->satisfaction)) ? $record->awareness_creation->satisfaction : '',
                    'comment' => (isset($record->awareness_creation) && isset($record->awareness_creation->comment)) ? $record->awareness_creation->comment : '',


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
     * Export Monthly Report Excel
     */

     public function monthlyReportExcelExport(Request $request, $id){

        $report_data=Member::with([
            'campaign.question',
            'disposition.disposition_type',
            'user',
            'contact',
            'schedule',
            'report.question.answer',
            'report.answer',
            'report.branch',
            'report.brand',
            'report.classification',
            'report.classification_type',
            'toyota_case.campaign',
            'toyota_case.voc_category',
            'toyota_case.classification_type',
            'toyota_case.classification'
        ])
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
        })
        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
            $q->whereHas('brand', function($q) use ($request)  {
                $q->where('id','like','%'.$request->brand.'%');
        });

        })
        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request){
            $q->whereHas('branch', function($q) use ($request)  {
                $q->where('id','like','%'.$request->branch.'%');
        });

        })
        ->whereNotNull('disposition_id')
        ->where('campaign_id', $id)
        ->get()->toArray();

        $report_array[]=array(
           'Date of Vehicle Delivery',	
           'Customer Name',	
           'Company Name',	
           'Customer Tel.',	
           'Customer Classification',
           'Reg. No',	
           'VIN',
           'Model',
           'New/Used',
           'Service Advisor',
           'Branch',
           'Brand',
           'DBM Order Code',
           'Job Card Number',
           'Completed Work Summary',
           'Gatepass Status',
           'Survey Type',               
           'On a scale of 0-10 (0=Very Unlikely, 10=Very Likely) - How likely is it that you would recommend Toyota Kenya to family, friends, colleagues?',
           'Please tell us why you gave this score.	',
           'Category [NPS VOC] 	',
           'Did you receive an explanation of the actual work after the service was completed?	',
           'Did your Service Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?',
           'Was your vehicle fixed right?	',
           'Were the repairs/Maintenance completed within the advised time?',
           'Do you feel the time taken to service/repair your vehicle was reasonable?	',
           'VOC Comment',
           'Type',
           'Department',
           'Comment Summary',
           'Action Required',
           'Status',
           'Staff Comments',
           'Call Date',
           'Call Time',
           'Agent',
           'Interval',
           'Call Status',
           'Disposition',
           'CallBack Date',
           'CallBack Time',
           'Call Attempts',
        );

        foreach($report_data as $record){


            $q=array();

            foreach($record->report()->whereNotIn('question_priority',[1,2,3,count($record->campaign->question)])->get() as $report){

                 if($report->question_priority == 5){
                             $q[]=$report->text_box_answer;
                         }else{
                           $q[]=isset($report->answer) ? ucfirst(\Illuminate\Support\Str::lower($report->answer->real_answer)) : '';
                 }
             }

         
        if(isset($q[0]) && ($q[0]>= 0 && $q[0] <= 6)){
             $type='Detractor';
         } else if(isset($q[0]) && ($q[0] == 7 || $q[0] == 8)){
             $type='Passive';
         } else if( isset($q[0]) && ($q[0] == 9 || $q[0] == 10)){
             $type='Promoter';
         }else if(empty($q[0])) {
             $type='';
         }else{
             $type='';
         }



            $report_array[]=array(
                'Date of Vehicle Delivery'=>$record->contact->date_of_delivery,	
                'Customer Name'=>$record->contact->customer,	
                'Company Name'=>$record->contact->customer_description,	
                'Customer Tel.'=>$record->contact->telephone_one,	
                'Customer Classification'=>$record->contact->cust_classification,
                'Reg. No'=>$record->contact->license_plate_number,	
                'VIN'=>$record->contact->vin_number,
                'Model'=>$record->contact->vehicle_model,
                'New/Used'=>$record->contact->new_used_vehicle,
                'Service Advisor'=>\App\Models\User::query()->firstWhere('pf_no',$record->contact->created_by)->name,
                'Branch'=>$record->branch->name,
                'Brand'=> $record->brand->name,
                'DBM Order Code'=>$record->contact->reason_for_visit,
                'Job Card Number'=>$record->contact->order_number,
                'Completed Work Summary'=>$record->contact->header_text,
                'Gatepass Status'=>$record->contact->gate_pass_ind,
                'Survey Type'=>$record->campaign->name,               
                'On a scale of 0-10 (0=Very Unlikely, 10=Very Likely) - How likely is it that you would recommend Toyota Kenya to family, friends, colleagues?'=>isset($q[0]) ? $q[0] : '',
                'Please tell us why you gave this score.'=>isset($q[1]) ? $q[1] : '',
                'Category [NPS VOC]'=>$type,
                'Did you receive an explanation of the actual work after the service was completed?'=>isset($q[2]) ? $q[2] : '',
                'Did your Service Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?'=>isset($q[3]) ? $q[3] : '',
                'Was your vehicle fixed right?'=>isset($q[4]) ? $q[4] : '',
                'Were the repairs/Maintenance completed within the advised time?'=>isset($q[5]) ? $q[5] : '',
                'Do you feel the time taken to service/repair your vehicle was reasonable?'=>isset($q[6]) ? $q[6] : '',
                'VOC Comment',
                'Type',
                'Department',
                'Comment Summary',
                'Action Required',
                'Status',
                'Staff Comments',
                'Call Date',
                'Call Time',
                'Agent',
                'Interval',
                'Call Status',
                'Disposition',
                'CallBack Date',
                'CallBack Time',
                'Call Attempts',
             );


             /*
             'date_of_delivery' => $record->contact->date_of_delivery,
                    'customer'=>$record->contact->customer,

                    'customer_description'=>$record->contact->customer_description, //contact_person


                    'telephone_one'=>$record->contact->telephone_one,


                    'cust_classification'=>$record->contact->cust_classification,


                    'license_plate_number'=>$record->contact->license_plate_number,

                    
                    'vin_number'=>$record->contact->vin_number,
                    'vehicle_model'=>$record->contact->vehicle_model,
                    'new_used_vehicle'=>$record->contact->new_used_vehicle,
                    'name'=>isset($record->contact->created_by) ? \App\Models\User::query()->firstWhere('pf_no',$record->contact->created_by)->name : '',
                    'branch'=>isset($record->branch->name) ? $record->branch->name : $record->branch_id,
                    'brand' => isset($record->brand->name) ? $record->brand->name : $record->brand_id,
                    'reason_for_visit'=>$record->contact->reason_for_visit,
                    'order_number'=>$record->contact->order_number,
                    'header_text'=>$record->contact->header_text,
                    'gate_pass_ind'=>$record->contact->gate_pass_ind,
                    'campaign'=>$record->campaign->name,
                    'q4'=>isset($q[0]) ? $q[0] : '',
                    'q5'=>isset($q[1]) ? $q[1] : '', 
                    'q5c'=>$type,
                    'q6'=>isset($q[2]) ? $q[2] : '',
                    'q7'=>isset($q[3]) ? $q[3] : '',
                    'q8'=>isset($q[4]) ? $q[4] : '', 
                    'q9'=>isset($q[5]) ? $q[5] : '',
                    'q10'=>isset($q[6]) ? $q[6] : '',
                    'q11'=>isset($q[7]) ? $q[7] : '',
                    'voc_customer'=>isset($record->toyota_case) ? $record->toyota_case->voc_customer : '',
                    'classification_type'=>isset($record->toyota_case) ? $record->toyota_case->classification_type->name: '',
                    'campaign' =>isset($record->toyota_case) ? $record->toyota_case->campaign->name : '',
                    'classification'=>isset($record->toyota_case) ? $record->toyota_case->classification->name : '',
                    'action' => isset($record->toyota_case) ? ucfirst(\Illuminate\Support\Str::lower($record->toyota_case->action)) : '',
                    'is_closed'=>isset($record->toyota_case) ? $record->toyota_case->is_closed? 'Closed' : 'Open' : '',
                    'comments'=>isset($record->toyota_case) ? $record->comments : '',
                    'updated_at'=>\Carbon\Carbon::parse($record->updated_at)->format('m/d/Y'),
                    'time' => \Carbon\Carbon::parse($record->updated_at)->format('h:i a'),
                    'username'=>isset($record->user) ? $record->user->name: '',
                    'hour'=>\Carbon\Carbon::parse($record->updated_at)->format('H'),
                    'disposition_type'=>$record->disposition->disposition_type->name ,
                    'disposition'=>$record->disposition->name,
                    'next_call_scheduled_at'=>$record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('m/d/Y') :'',
                    'next_call_scheduled_at_time'=>$record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('h:i a') :'',
                    'attempts'=> number_format($record->attempts)
             */


        }






     }


    /**
    * VOC Reports
    */  
    public function vocReports(Request $request, $id){


        $draw = $request->draw;
        $start = $request->start;
        $rowperpage = $request->length;

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;

        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }

        $totalRecords=ToyotaCase::with([
                        'user',
                        'campaign',
                        'branch',
                        'brand',
                        'member.contact',
                        'voc_category',
                        'classification_type',
                        'classification',
                        'escalate.user',
        ])
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
        })
        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
            $q->whereHas('brand', function($q) use ($request)  {
                $q->where('id','like','%'.$request->brand.'%');
        });

        })
        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
            if($branchRequestID == $westlandBranchID){
                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                    $q->whereHas('branch', function($query) use ($request)  {
                        $query->where('id','like','%'.$request->branch.'%');
                    });
                    $q->whereHas('brand', function($query) {
                        $query->where('slug','toyota');
                    });
                }else{
                    $q->whereHas('branch', function($q) {
                        $q->whereRaw("true = false");
                    });
                }
            }elseif($branchRequestID == $kisumuBranchID){
                if(empty($brandRequest)){ 
                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                        $query->where('id',$westlandBranchID);
                        $query->orWhere('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                        $query->Where('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                        $query->Where('id',$westlandBranchID);
                    });
                }
            }else{
                $q->whereHas('branch', function($q) use ($request)  {
                    $q->where('id','like','%'.$request->branch.'%');
                });
            }
        })
        ->where('campaign_id', $id)
        ->whereHas('member', function($q){
            $q->where('is_complete',true);
        })
        ->count();

        $totalRecordswithFilter=$totalRecords;

        $records=ToyotaCase::orderBy($columnName,$columnSortOrder)
                            ->with([
                                'user',
                                'campaign',
                                'branch',
                                'brand',
                                'member.contact',
                                'voc_category',
                                'classification_type',
                                'classification',
                                'escalate.user',
                            ])
                            ->where('campaign_id', $id)
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                $q->whereHas('brand', function($q) use ($request)  {
                                    $q->where('id','like','%'.$request->brand.'%');
                            });
                    
                            })
                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                if($branchRequestID == $westlandBranchID){
                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                        $q->whereHas('branch', function($query) use ($request)  {
                                            $query->where('id','like','%'.$request->branch.'%');
                                        });
                                        $q->whereHas('brand', function($query) {
                                            $query->where('slug','toyota');
                                        });
                                    }else{
                                        $q->whereHas('branch', function($q) {
                                            $q->whereRaw("true = false");
                                        });
                                    }
                                }elseif($branchRequestID == $kisumuBranchID){
                                    if(empty($brandRequest)){ 
                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                            $query->where('id',$westlandBranchID);
                                            $query->orWhere('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                            $query->Where('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                            $query->Where('id',$westlandBranchID);
                                        });
                                    }
                                }else{
                                    $q->whereHas('branch', function($q) use ($request)  {
                                        $q->where('id','like','%'.$request->branch.'%');
                                    });
                                }
                            })
                            ->whereHas('member', function($q){
                                $q->where('is_complete',true);
                            })
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();
            $data_arr = array();
            $t=0;
          

            foreach($records as $record){
                $t+=1;

                if(empty($record->comments) && ($record->classification_type->slug == "enquiries" || $record->classification_type->slug == "negative")){
                    $is_closed = 'Open';
                }else{
                    $is_closed = 'Closed';
                }

                // fixing the westland and kismu branch bug from backend because cant change the database
                $branch = '';
                if(isset($record->branch)){
                    if($record->brand->slug != 'toyota' && ($record->branch->slug == 'kisumu' || $record->branch->slug == 'westlands')){
                        $branch = 'Kisumu';
                    }elseif(isset($record->branch->name)){
                        $branch = $record->branch->name;
                    }else{
                        $branch = $record->branch_id;
                    }
                }

                $data_arr[] =array(
                    "id" =>'&nbsp;&nbsp;'.$t,
                    'order_number' => $record->member->contact->order_number,
                    'created_at'=>date('Y-m-d',strtotime($record->created_at)),
                    'CSI'=>'CSI',
                    'customer_description'=>$record->member->contact->customer_description,
                    'vin_number'=>$record->member->contact->vin_number,
                    'license_plate_number'=>$record->member->contact->license_plate_number,
                    'branch'=>isset($branch) ? $branch : "",
                    'brand'=>isset($record->brand->name) ? $record->brand->name : $record->brand_id,
                    'name'=>isset($record->member->contact->created_by) ?  isset(\App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name) ? \App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name : '' : '',
                    'voc_customer'=>$record->voc_customer,
                    'classification_type'=>$record->classification_type->name,
                    'campaign' => $record->campaign->name,
                    'classification_name'=>$record->classification->name,
                    'action'=>ucfirst(\Illuminate\Support\Str::lower($record->action)),
                    'is_closed'=>$is_closed,
                    'comments'=>(isset($record->comments)) ? $record->comments : '',
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
    * CSI Reports
    */  
    public function csiReports(Request $request, $id){

        $draw = $request->draw;
        $start = $request->start;
        $rowperpage = $request->length;

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;

        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }

        $totalRecords=ToyotaCase::with([
            'user',
            'campaign',
            'member.contact',
            'branch',
            'brand',
            'escalate.user',
            'campaign',
            'voc_category',
            'classification_type',
            'classification'
        ])
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
        })
        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
            $q->whereHas('brand', function($q) use ($request)  {
                $q->where('id','like','%'.$request->brand.'%');
        });

        })
        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
            if($branchRequestID == $westlandBranchID){
                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                    $q->whereHas('branch', function($query) use ($request)  {
                        $query->where('id','like','%'.$request->branch.'%');
                    });
                    $q->whereHas('brand', function($query) {
                        $query->where('slug','toyota');
                    });
                }else{
                    $q->whereHas('branch', function($q) {
                        $q->whereRaw("true = false");
                    });
                }
            }elseif($branchRequestID == $kisumuBranchID){
                if(empty($brandRequest)){ 
                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                        $query->where('id',$westlandBranchID);
                        $query->orWhere('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                        $query->Where('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                        $query->Where('id',$westlandBranchID);
                    });
                }
            }else{
                $q->whereHas('branch', function($q) use ($request)  {
                    $q->where('id','like','%'.$request->branch.'%');
                });
            }
        })
        ->whereHas('member', function($q){
            $q->where('is_complete',true);
        })
        ->where('campaign_id', $id)
        ->count();

        $totalRecordswithFilter=$totalRecords;

        $records=ToyotaCase::orderBy($columnName,$columnSortOrder)
                            ->with([
                                'user',
                                'campaign',
                                'member.contact',
                                'branch',
                                'brand',
                                'escalate.user',
                                'campaign',
                                'voc_category',
                                'classification_type',
                                'classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                $q->whereHas('brand', function($q) use ($request)  {
                                    $q->where('id','like','%'.$request->brand.'%');
                            });

                            })
                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                if($branchRequestID == $westlandBranchID){
                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                        $q->whereHas('branch', function($query) use ($request)  {
                                            $query->where('id','like','%'.$request->branch.'%');
                                        });
                                        $q->whereHas('brand', function($query) {
                                            $query->where('slug','toyota');
                                        });
                                    }else{
                                        $q->whereHas('branch', function($q) {
                                            $q->whereRaw("true = false");
                                        });
                                    }
                                }elseif($branchRequestID == $kisumuBranchID){
                                    if(empty($brandRequest)){ 
                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                            $query->where('id',$westlandBranchID);
                                            $query->orWhere('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                            $query->Where('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                            $query->Where('id',$westlandBranchID);
                                        });
                                    }
                                }else{
                                    $q->whereHas('branch', function($q) use ($request)  {
                                        $q->where('id','like','%'.$request->branch.'%');
                                    });
                                }
                            })
                            ->whereHas('member', function($q){
                                $q->where('is_complete',true);
                            })
                            ->where('campaign_id', $id)
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();
            $data_arr = array();
            $t=0;



            foreach($records as $record){
                $t+=1;

                if(empty($record->comments) && ($record->classification_type->slug == "enquiries" || $record->classification_type->slug == "negative")){
                    $is_closed = 'Open';
                }else{
                    $is_closed = 'Closed';
                }

                // fixing the westland and kismu branch bug from backend because cant change the database
                $branch = '';
                if(isset($record->branch)){
                    if($record->brand->slug != 'toyota' && ($record->branch->slug == 'kisumu' || $record->branch->slug == 'westlands')){
                        $branch = 'Kisumu';
                    }elseif(isset($record->branch->name)){
                        $branch = $record->branch->name;
                    }else{
                        $branch = $record->branch_id;
                    }
                }

                $data_arr[] =array(
                    "id" =>'&nbsp;&nbsp;'.$t,
                    'country' => 'Kenya',
                    'updated_at'=>date('m/d/Y',strtotime($record->member->updated_at)),
                    'CSI'=>'CSI',
                    'brand'=>isset($record->brand->name) ? $record->brand->name : $record->brand_id,
                    'order_number'=>$record->member->contact->order_number,
                    'customer_description'=>$record->member->contact->customer_description,
                    'vin_number'=>$record->member->contact->vin_number,
                    'license_plate_number'=>$record->member->contact->license_plate_number,
                    'branch'=>isset($branch) ? $branch : "",
                    'name'=>isset($record->member->contact->created_by)? isset(\App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name) ? \App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name: '' : '',
                    'voc_customer'=>$record->voc_customer,
                    'classification_type' => $record->classification_type->name,
                    'campaign'=>$record->campaign->name,
                    'classification_name'=>$record->classification->name,
                    'action'=>ucfirst(\Illuminate\Support\Str::lower($record->action)),
                    'is_closed'=>$is_closed,
                    'comments'=>(isset($record->comments)) ? $record->comments : '',
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
    * NPS Reports
    */  
    public function npsReports(Request $request, $id){


        $draw = $request->draw;
        $start = $request->start;
        $rowperpage = $request->length;

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;

        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }
        
        $totalRecords=ToyotaCase::with([
            'user',
            'campaign',
            'member.contact',
            'member.report.answer',
            'branch',
            'brand',
            'escalate.user',
            'campaign',
            'voc_category',
            'classification_type',
            'classification'
        ])
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
        })
        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
            $q->whereHas('brand', function($q) use ($request)  {
                $q->where('id','like','%'.$request->brand.'%');
        });

        })
        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
            if($branchRequestID == $westlandBranchID){
                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                    $q->whereHas('branch', function($query) use ($request)  {
                        $query->where('id','like','%'.$request->branch.'%');
                    });
                    $q->whereHas('brand', function($query) {
                        $query->where('slug','toyota');
                    });
                }else{
                    $q->whereHas('branch', function($q) {
                        $q->whereRaw("true = false");
                    });
                }
            }elseif($branchRequestID == $kisumuBranchID){
                if(empty($brandRequest)){ 
                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                        $query->where('id',$westlandBranchID);
                        $query->orWhere('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                        $query->Where('id',$kisumuBranchID);
                    });
                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                        $query->Where('id',$westlandBranchID);
                    });
                }
            }else{
                $q->whereHas('branch', function($q) use ($request)  {
                    $q->where('id','like','%'.$request->branch.'%');
                });
            }
        })
        ->where('campaign_id', $id)
        ->whereHas('member', function($q){
            $q->where('is_complete',true);
        })
        ->count();

        $totalRecordswithFilter=$totalRecords;

        $records=ToyotaCase::orderBy($columnName,$columnSortOrder)
                            ->with([
                                'user',
                                'campaign',
                                'member.contact',
                                'member.report.answer',
                                'branch',
                                'brand',
                                'escalate.user',
                                'campaign',
                                'voc_category',
                                'classification_type',
                                'classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                $q->whereHas('brand', function($q) use ($request)  {
                                    $q->where('id','like','%'.$request->brand.'%');
                            });

                            })
                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                if($branchRequestID == $westlandBranchID){
                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                        $q->whereHas('branch', function($query) use ($request)  {
                                            $query->where('id','like','%'.$request->branch.'%');
                                        });
                                        $q->whereHas('brand', function($query) {
                                            $query->where('slug','toyota');
                                        });
                                    }else{
                                        $q->whereHas('branch', function($q) {
                                            $q->whereRaw("true = false");
                                        });
                                    }
                                }elseif($branchRequestID == $kisumuBranchID){
                                    if(empty($brandRequest)){ 
                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                            $query->where('id',$westlandBranchID);
                                            $query->orWhere('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                            $query->Where('id',$kisumuBranchID);
                                        });
                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                            $query->Where('id',$westlandBranchID);
                                        });
                                    }
                                }else{
                                    $q->whereHas('branch', function($q) use ($request)  {
                                        $q->where('id','like','%'.$request->branch.'%');
                                    });
                                }
                            })
                            ->where('campaign_id', $id)
                            ->whereHas('member', function($q){
                                $q->where('is_complete',true);
                            })
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();
            $data_arr = array();
            $t=0;


            foreach($records as $record){
                $t+=1;

                if(empty($record->comments) && ($record->classification_type->slug == "enquiries" || $record->classification_type->slug == "negative")){
                    $is_closed = 'Open';
                }else{
                    $is_closed = 'Closed';
                }

                $report = $record->member->report()->firstWhere('question_priority',5);
                $nps = isset($record->member->report()->firstWhere('question_priority',4)->answer->real_answer) ? (int)$record->member->report()->firstWhere('question_priority',4)->answer->real_answer : '';

                if($nps >= 0 && $nps <= 6){
                            $type="Detractor";
                }
                elseif($nps == 7 || $nps == 8){
                    $type="Passive";
                }elseif($nps == 9 || $nps == 10){
                    $type="Promoter";
                }else{
                    $type="N/A";
                }

                // fixing the westland and kismu branch bug from backend because cant change the database
                $branch = '';
                if(isset($record->branch)){
                    if($record->brand->slug != 'toyota' && ($record->branch->slug == 'kisumu' || $record->branch->slug == 'westlands')){
                        $branch = 'Kisumu';
                    }elseif(isset($record->branch->name)){
                        $branch = $record->branch->name;
                    }else{
                        $branch = $record->branch_id;
                    }
                }

                $data_arr[] =array(
                    "id" =>'&nbsp;&nbsp;'.$t,
                    'country' => 'Kenya',
                    'updated_at'=>date('m/d/Y',strtotime($record->member->updated_at)),
                    'type'=>$type,
                    'brand'=>isset($record->brand->name) ? $record->brand->name : $record->brand_id,
                    'order_number'=>$record->member->contact->order_number,
                    'customer_description'=>$record->member->contact->customer_description,
                    'vin_number'=>$record->member->contact->vin_number,
                    'license_plate_number'=>$record->member->contact->license_plate_number,
                    'branch'=>isset($branch) ? $branch : "",
                    'name'=>isset($record->member->contact->created_by) ? isset(\App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name) ? \App\Models\User::query()->firstWhere('pf_no',$record->member->contact->created_by)->name : '' :'',
                    'text_box_answer'=>$report->text_box_answer,
                    'classification_type' => (isset($report->classification_type) && !empty($report->classification_type->name)) ? $report->classification_type->name : "",
                    'campaign'=>(isset($report->campaign) && !empty($report->campaign->name)) ? $report->campaign->name : "",
                    'classification_name'=>(isset($report->classification) && !empty($report->classification->name)) ? $report->classification->name : "",
                    'action'=>ucfirst(\Illuminate\Support\Str::lower($record->action)),
                    'is_closed'=>$is_closed,
                    'comments'=>(isset($record->comments)) ? $record->comments : '',
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
    * VOC Reports
    */  
    public function advisorcsiReports(Request $request, $id){
        $campaign = Campaign::findOrFail($id);

        $records = array();
    
        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }
        /**
         * Question No 6
         */
        $question_six_yes   = Report::select('member_id')
                                ->where('question_priority',6)
                                ->whereHas('member', function($q) use ($id){
                                    $q->where('is_complete',true);
                                    $q->where('campaign_id', $id);

                                })
                                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                })
                                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                    if($branchRequestID == $westlandBranchID){
                                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                            $q->whereHas('branch', function($query) use ($request)  {
                                                $query->where('id','like','%'.$request->branch.'%');
                                            });
                                            $q->whereHas('brand', function($query) {
                                                $query->where('slug','toyota');
                                            });
                                        }else{
                                            $q->whereHas('branch', function($q) {
                                                $q->whereRaw("true = false");
                                            });
                                        }
                                    }elseif($branchRequestID == $kisumuBranchID){
                                        if(empty($brandRequest)){ 
                                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                $query->where('id',$westlandBranchID);
                                                $query->orWhere('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                $query->Where('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                $query->Where('id',$westlandBranchID);
                                            });
                                        }
                                    }else{
                                        $q->whereHas('branch', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->branch.'%');
                                        });
                                    }
                                })
                                ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                    $q->whereHas('member.contact', function($q) use ($request)  {
                                        $q->where('created_by', $request->advisor);
                                    });
                                })
                                ->whereHas('answer', function($q){
                                    $q->where('answer','like','%YES%');
                                })
                                ->get()
                                ->groupBy(['member.contact.created_by', 'member.branch.name']);

        foreach($question_six_yes as $createdByKeySixYes => $createdByValueSixYes){
            foreach($createdByValueSixYes as $branchKeySixYes => $branchValueSixYes){
                $question_six_yes_CBB = array($createdByKeySixYes,$branchKeySixYes);
                $newRecordSixYes = true;
                foreach($records as $recordValuesSixYes ){
                    if(empty(array_diff($question_six_yes_CBB,$recordValuesSixYes))){
                        $newRecordSixYes = false;
                    }
                }
                if($newRecordSixYes){
                    array_push($records,$question_six_yes_CBB);
                }
            }
        }
        
        $question_six_no    = Report::select('member_id')
                                ->where('question_priority',6)
                                ->whereHas('member', function($q) use ($id){
                                    $q->where('is_complete',true);
                                    $q->where('campaign_id', $id);

                                })
                                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                })
                                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                    if($branchRequestID == $westlandBranchID){
                                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                            $q->whereHas('branch', function($query) use ($request)  {
                                                $query->where('id','like','%'.$request->branch.'%');
                                            });
                                            $q->whereHas('brand', function($query) {
                                                $query->where('slug','toyota');
                                            });
                                        }else{
                                            $q->whereHas('branch', function($q) {
                                                $q->whereRaw("true = false");
                                            });
                                        }
                                    }elseif($branchRequestID == $kisumuBranchID){
                                        if(empty($brandRequest)){ 
                                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                $query->where('id',$westlandBranchID);
                                                $query->orWhere('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                $query->Where('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                $query->Where('id',$westlandBranchID);
                                            });
                                        }
                                    }else{
                                        $q->whereHas('branch', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->branch.'%');
                                        });
                                    }
                                })
                                ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                    $q->whereHas('member.contact', function($q) use ($request)  {
                                        $q->where('created_by', $request->advisor);
                                    });
                                })
                                ->whereHas('answer', function($q){
                                    $q->where('answer','like','%NO%');
                                })
                                ->get()
                                ->groupBy(['member.contact.created_by', 'member.branch.name']);

        foreach($question_six_no as $createdByKeySixNo => $createdByValueSixNo){
            foreach($createdByValueSixNo as $branchKeySixNo => $branchValueSixNo){
                $question_six_no_CBB = array($createdByKeySixNo,$branchKeySixNo);
                $newRecordSixNo = true;
                foreach($records as $recordValuesSixNo ){
                    if(empty(array_diff($question_six_no_CBB,$recordValuesSixNo))){
                        $newRecordSixNo = false;
                    }
                }
                if($newRecordSixNo){
                    array_push($records,$question_six_no_CBB);
                }
            }
        }
        
        /**
         * Question No 7
         */
        $question_seven_yes   = Report::select('member_id')
                                ->where('question_priority',7)
                                ->whereHas('member', function($q) use ($id){
                                    $q->where('is_complete',true);
                                    $q->where('campaign_id', $id);

                                })
                                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                })
                                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                    if($branchRequestID == $westlandBranchID){
                                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                            $q->whereHas('branch', function($query) use ($request)  {
                                                $query->where('id','like','%'.$request->branch.'%');
                                            });
                                            $q->whereHas('brand', function($query) {
                                                $query->where('slug','toyota');
                                            });
                                        }else{
                                            $q->whereHas('branch', function($q) {
                                                $q->whereRaw("true = false");
                                            });
                                        }
                                    }elseif($branchRequestID == $kisumuBranchID){
                                        if(empty($brandRequest)){ 
                                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                $query->where('id',$westlandBranchID);
                                                $query->orWhere('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                $query->Where('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                $query->Where('id',$westlandBranchID);
                                            });
                                        }
                                    }else{
                                        $q->whereHas('branch', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->branch.'%');
                                        });
                                    }
                                })
                                ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                    $q->whereHas('member.contact', function($q) use ($request)  {
                                        $q->where('created_by', $request->advisor);
                                    });
                                })
                                ->whereHas('answer', function($q){
                                    $q->where('answer','like','%YES%');
                                })
                                ->get()
                                ->groupBy(['member.contact.created_by', 'member.branch.name']);
            
        foreach($question_seven_yes as $createdByKeySevenYes => $createdByValueSevenYes){
            foreach($createdByValueSevenYes as $branchKeySevenYes => $branchValueSevenYes){
                $question_seven_yes_CBB = array($createdByKeySevenYes,$branchKeySevenYes);
                $newRecordSevenYes = true;
                foreach($records as $recordValuesSevenYes ){
                    if(empty(array_diff($question_seven_yes_CBB,$recordValuesSevenYes))){
                        $newRecordSevenYes = false;
                    }
                }
                if($newRecordSevenYes){
                    array_push($records,$question_seven_yes_CBB);
                }
            }
        }
        
        $question_seven_no    = Report::select('member_id')
                                ->where('question_priority',7)
                                ->whereHas('member', function($q) use ($id){
                                    $q->where('is_complete',true);
                                    $q->where('campaign_id', $id);

                                })
                                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                })
                                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                    if($branchRequestID == $westlandBranchID){
                                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                            $q->whereHas('branch', function($query) use ($request)  {
                                                $query->where('id','like','%'.$request->branch.'%');
                                            });
                                            $q->whereHas('brand', function($query) {
                                                $query->where('slug','toyota');
                                            });
                                        }else{
                                            $q->whereHas('branch', function($q) {
                                                $q->whereRaw("true = false");
                                            });
                                        }
                                    }elseif($branchRequestID == $kisumuBranchID){
                                        if(empty($brandRequest)){ 
                                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                $query->where('id',$westlandBranchID);
                                                $query->orWhere('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                $query->Where('id',$kisumuBranchID);
                                            });
                                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                $query->Where('id',$westlandBranchID);
                                            });
                                        }
                                    }else{
                                        $q->whereHas('branch', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->branch.'%');
                                        });
                                    }
                                })
                                ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                    $q->whereHas('member.contact', function($q) use ($request)  {
                                        $q->where('created_by', $request->advisor);
                                    });
                                })
                                ->whereHas('answer', function($q){
                                    $q->where('answer','like','%NO%');
                                })
                                ->get()
                                ->groupBy(['member.contact.created_by', 'member.branch.name']);

        foreach($question_seven_no as $createdByKeySevenNo => $createdByValueSevenNo){
            foreach($createdByValueSevenNo as $branchKeySevenNo => $branchValueSevenNo){
                $question_seven_no_CBB = array($createdByKeySevenNo,$branchKeySevenNo);
                $newRecordSevenNo = true;
                foreach($records as $recordValuesSevenNo ){
                    if(empty(array_diff($question_seven_no_CBB,$recordValuesSevenNo))){
                        $newRecordSevenNo = false;
                    }
                }
                if($newRecordSevenNo){
                    array_push($records,$question_seven_no_CBB);
                }
            }
        }
                                
        if(!empty($campaign->slug) && $campaign->slug == 'sales'){
            /**
             * Question No 8
             */
            $question_eight_yes   = Report::select('member_id')
                                    ->where('question_priority',8)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);

                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                        $q->whereHas('member.contact', function($q) use ($request)  {
                                            $q->where('created_by', $request->advisor);
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%YES%');
                                    })
                                    ->get()
                                    ->groupBy(['member.contact.created_by', 'member.branch.name']);
            
            foreach($question_eight_yes as $createdByKeyEightYes => $createdByValueEightYes){
                foreach($createdByValueEightYes as $branchKeyEightYes => $branchValueEightYes){
                    $question_eight_yes_CBB = array($createdByKeyEightYes,$branchKeyEightYes);
                    $newRecordEightYes = true;
                    foreach($records as $recordValuesSevenYes ){
                        if(empty(array_diff($question_eight_yes_CBB,$recordValuesSevenYes))){
                            $newRecordEightYes = false;
                        }
                    }
                    if($newRecordEightYes){
                        array_push($records,$question_eight_yes_CBB);
                    }
                }
            }

            $question_eight_no    = Report::select('member_id')
                                    ->where('question_priority',8)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);

                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                        $q->whereHas('member.contact', function($q) use ($request)  {
                                            $q->where('created_by', $request->advisor);
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%NO%');
                                    })
                                    ->get()
                                    ->groupBy(['member.contact.created_by', 'member.branch.name']);

            foreach($question_eight_no as $createdByKeyEightNo => $createdByValueEightNo){
                foreach($createdByValueEightNo as $branchKeyEightNo => $branchValueEightNo){
                    $question_eight_no_CBB = array($createdByKeyEightNo,$branchKeyEightNo);
                    $newRecordEightNo = true;
                    foreach($records as $recordValuesEightNo ){
                        if(empty(array_diff($question_eight_no_CBB,$recordValuesEightNo))){
                            $newRecordEightNo = false;
                        }
                    }
                    if($newRecordEightNo){
                        array_push($records,$question_eight_no_CBB);
                    }
                }
            }
                                             
            /**
             * Question No 9
             */
            $question_nine_yes   = Report::select('member_id')
                                    ->where('question_priority',9)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);

                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                        $q->whereHas('member.contact', function($q) use ($request)  {
                                            $q->where('created_by', $request->advisor);
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%YES%');
                                    })
                                    ->get()
                                    ->groupBy(['member.contact.created_by', 'member.branch.name']);
            
            foreach($question_nine_yes as $createdByKeyNineYes => $createdByValueNineYes){
                foreach($createdByValueNineYes as $branchKeyNineYes => $branchValueNineYes){
                    $question_nine_yes_CBB = array($createdByKeyNineYes,$branchKeyNineYes);
                    $newRecordNineYes = true;
                    foreach($records as $recordValuesNineYes ){
                        if(empty(array_diff($question_nine_yes_CBB,$recordValuesNineYes))){
                            $newRecordNineYes = false;
                        }
                    }
                    if($newRecordNineYes){
                        array_push($records,$question_nine_yes_CBB);
                    }
                }
            }
                                 
            $question_nine_no    = Report::select('member_id')
                                    ->where('question_priority',9)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);

                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('advisor') && !empty($request->advisor)), function($q) use($request){
                                        $q->whereHas('member.contact', function($q) use ($request)  {
                                            $q->where('created_by', $request->advisor);
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%NO%');
                                    })
                                    ->get()
                                    ->groupBy(['member.contact.created_by', 'member.branch.name']);
                  
            foreach($question_nine_no as $createdByKeyNineNo => $createdByValueNineNo){
                foreach($createdByValueNineNo as $branchKeyNineNo => $branchValueNineNo){
                    $question_nine_no_CBB = array($createdByKeyNineNo,$branchKeyNineNo);
                    $newRecordNineNo = true;
                    foreach($records as $recordValuesNineNo ){
                        if(empty(array_diff($question_nine_no_CBB,$recordValuesNineNo))){
                            $newRecordNineNo = false;
                        }
                    }
                    if($newRecordNineNo){
                        array_push($records,$question_nine_no_CBB);
                    }
                }
            }
        }

        $data_arr = array();
        $t=0;
          
        if(!empty($campaign->slug) && $campaign->slug == 'sales'){
            foreach($records as $record){
                $q6yes  = (!empty($question_six_yes) && !empty($question_six_yes[$record[0]]) && !empty($question_six_yes[$record[0]][$record[1]])) ? count($question_six_yes[$record[0]][$record[1]]) : 0 ;

                $q6no   = (!empty($question_six_no) && !empty($question_six_no[$record[0]]) && !empty($question_six_no[$record[0]][$record[1]])) ? count($question_six_no[$record[0]][$record[1]]) : 0;

                $q7yes  = (!empty($question_seven_yes) && !empty($question_seven_yes[$record[0]]) && !empty($question_seven_yes[$record[0]][$record[1]])) ? count($question_seven_yes[$record[0]][$record[1]]) : 0;

                $q7no   = (!empty($question_seven_no) && !empty($question_seven_no[$record[0]]) && !empty($question_seven_no[$record[0]][$record[1]])) ? count($question_seven_no[$record[0]][$record[1]]) : 0;

                $q8yes  = (!empty($question_eight_yes) && !empty($question_eight_yes[$record[0]]) && !empty($question_eight_yes[$record[0]][$record[1]])) ? count($question_eight_yes[$record[0]][$record[1]]) : 0;

                $q8no   = (!empty($question_eight_no) && !empty($question_eight_no[$record[0]]) && !empty($question_eight_no[$record[0]][$record[1]])) ? count($question_eight_no[$record[0]][$record[1]]) : 0;

                $q9yes  = (!empty($question_nine_yes) && !empty($question_nine_yes[$record[0]]) && !empty($question_nine_yes[$record[0]][$record[1]])) ? count($question_nine_yes[$record[0]][$record[1]]) : 0;

                $q9no   = (!empty($question_nine_no) && !empty($question_nine_no[$record[0]]) && !empty($question_nine_no[$record[0]][$record[1]])) ? count($question_nine_no[$record[0]][$record[1]]) : 0;

                if(empty($q6yes) && empty($q6no) && empty($q7yes) && empty($q7no) && empty($q8yes) && empty($q8no) && empty($q9yes) && empty($q9no)){
                    continue;
                }

                $t+=1;

                $advisor = User::select('name')->where('pf_no',$record[0])->first();

                $totalYes = $q6yes + $q7yes + $q8yes + $q9yes;

                $totalNo = $q6no + $q7no + $q8no + $q9no;

                $total = $totalYes + $totalNo;

                $CSIScore = ($totalYes / $total) * 100;

                $data_arr[] =array(
                    "id"        => $t,
                    "advisor"   => (!empty($advisor) && !empty($advisor->name)) ? $advisor->name : 'N/A',
                    "branch"    => $record[1],
                    "q6yes"     => $q6yes,
                    "q6no"      => $q6no,
                    "q7yes"     => $q7yes,
                    "q7no"      => $q7no,
                    "q8yes"     => $q8yes,
                    "q8no"      => $q8no,
                    "q9yes"     => $q9yes,
                    "q9no"      => $q9no,
                    "totalyes"  => $totalYes,
                    "totalno"   => $totalNo,
                    "total"     => $total,
                    "csiscore"  => round($CSIScore) . '%'
                );
            }
        }else{
            foreach($records as $record){
                $q6yes  = (!empty($question_six_yes) && !empty($question_six_yes[$record[0]]) && !empty($question_six_yes[$record[0]][$record[1]])) ? count($question_six_yes[$record[0]][$record[1]]) : 0 ;

                $q6no   = (!empty($question_six_no) && !empty($question_six_no[$record[0]]) && !empty($question_six_no[$record[0]][$record[1]])) ? count($question_six_no[$record[0]][$record[1]]) : 0;

                $q7yes  = (!empty($question_seven_yes) && !empty($question_seven_yes[$record[0]]) && !empty($question_seven_yes[$record[0]][$record[1]])) ? count($question_seven_yes[$record[0]][$record[1]]) : 0;

                $q7no   = (!empty($question_seven_no) && !empty($question_seven_no[$record[0]]) && !empty($question_seven_no[$record[0]][$record[1]])) ? count($question_seven_no[$record[0]][$record[1]]) : 0;

                if(empty($q6yes) && empty($q6no) && empty($q7yes) && empty($q7no)){
                    continue;
                }

                $t+=1;

                $advisor = User::select('name')->where('pf_no',$record[0])->first();

                $totalYes = $q6yes + $q7yes;

                $totalNo = $q6no + $q7no;

                $total = $totalYes + $totalNo;

                $CSIScore = ($totalYes / $total) * 100;

                $data_arr[] =array(
                    "id"        => $t,
                    "advisor"   => (!empty($advisor) && !empty($advisor->name)) ? $advisor->name : 'N/A',
                    "branch"    => $record[1],
                    "q6yes"     => $q6yes,
                    "q6no"      => $q6no,
                    "q7yes"     => $q7yes,
                    "q7no"      => $q7no,
                    "totalyes"  => $totalYes,
                    "totalno"   => $totalNo,
                    "total"     => $total,
                    "csiscore"  => round($CSIScore) . '%'
                );
            }
        }
        

        $response = array(
            "aaData" => $data_arr
        );
             
        echo json_encode($response);
        exit;  
    }

    public function overallCSI(Request $request){
        $request->flash();
        $campaigns               = Campaign::all()->unique('slug')->values();
        $branches=Branch::all();
        $brands=Brand::all();
        $salesQuestionsCount     = [
            'yes'    => [],
            'no'    => [],
        ];
        $salesQuestions = [
            'Did you feel that the Sales Consultant explained the products, its features and functionality to you BEFORE purchase?',
            'Did the Sales Consultant who delivered your vehicle book your 1000km free service & call to remind you?',
            'Did the Sales Consultant brief you about the BOOK OF LIFE, including Warranty and Maintenance?',
            'Did your Sales Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?'
        ];
 
        $partsQuestionsCount     = [
            'yes'    => [],
            'no'    => [],
        ];
        $partsQuestions = [
            'Were you satisfied with the documentation process from Quotation to payment?',
            'Were the correct parts supplied?',
            'Did we have all the parts you required?',
        ];
 
        $BPQuestionsCount        = [
            'yes'    => [],
            'no'    => [],
        ];
        $BPQuestions = [
            'Did you receive an explanation of the actual work after the service/repair was completed?',
            'Did your Service Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?',
        ];
 
        $servicesQuestionsCount  = [
            'yes'    => [],
            'no'    => [],
        ];
        $servicesQuestions = [
            'Did you receive an explanation of the actual work after the repairs were completed?',
            'Did your Service Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?',
        ];

        
    
        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }
         
         foreach ($campaigns as $campaign) {
             $id = $campaign->id;
             /**
              * Question No 6
              */
             $question_six_yes   = Report::where('question_priority',6)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%YES%');
                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                        $q->whereHas('brand', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->brand.'%');
                                        });
                                    })
                                    ->count();
 
             $question_six_no    = Report::where('question_priority',6)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);
                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                        $q->whereHas('brand', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->brand.'%');
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%NO%');
                                    })
                                    ->count();
 
             
             /**
              * Question No 7
              */
             $question_seven_yes   = Report::where('question_priority',7)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);
                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                        $q->whereHas('brand', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->brand.'%');
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%YES%');
                                    })
                                    ->count();
                 
             $question_seven_no    = Report::where('question_priority',7)
                                    ->whereHas('member', function($q) use ($id){
                                        $q->where('is_complete',true);
                                        $q->where('campaign_id', $id);
                                    })
                                    ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                        $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                    })
                                    ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                        if($branchRequestID == $westlandBranchID){
                                            if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                $q->whereHas('branch', function($query) use ($request)  {
                                                    $query->where('id','like','%'.$request->branch.'%');
                                                });
                                                $q->whereHas('brand', function($query) {
                                                    $query->where('slug','toyota');
                                                });
                                            }else{
                                                $q->whereHas('branch', function($q) {
                                                    $q->whereRaw("true = false");
                                                });
                                            }
                                        }elseif($branchRequestID == $kisumuBranchID){
                                            if(empty($brandRequest)){ 
                                                $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                    $query->where('id',$westlandBranchID);
                                                    $query->orWhere('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                    $query->Where('id',$kisumuBranchID);
                                                });
                                            }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                    $query->Where('id',$westlandBranchID);
                                                });
                                            }
                                        }else{
                                            $q->whereHas('branch', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->branch.'%');
                                            });
                                        }
                                    })
                                    ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                        $q->whereHas('brand', function($q) use ($request)  {
                                            $q->where('id','like','%'.$request->brand.'%');
                                        });
                                    })
                                    ->whereHas('answer', function($q){
                                        $q->where('answer','like','%NO%');
                                    })
                                    ->count();
                                     
             if(!empty($campaign->slug) && ($campaign->slug == 'sales' || $campaign->slug == 'parts')){
                 /**
                  * Question No 8
                  */
                 $question_eight_yes   = Report::where('question_priority',8)
                                        ->whereHas('member', function($q) use ($id){
                                            $q->where('is_complete',true);
                                            $q->where('campaign_id', $id);
                                        })
                                        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                        })
                                        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                            if($branchRequestID == $westlandBranchID){
                                                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                    $q->whereHas('branch', function($query) use ($request)  {
                                                        $query->where('id','like','%'.$request->branch.'%');
                                                    });
                                                    $q->whereHas('brand', function($query) {
                                                        $query->where('slug','toyota');
                                                    });
                                                }else{
                                                    $q->whereHas('branch', function($q) {
                                                        $q->whereRaw("true = false");
                                                    });
                                                }
                                            }elseif($branchRequestID == $kisumuBranchID){
                                                if(empty($brandRequest)){ 
                                                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                        $query->where('id',$westlandBranchID);
                                                        $query->orWhere('id',$kisumuBranchID);
                                                    });
                                                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                        $query->Where('id',$kisumuBranchID);
                                                    });
                                                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                        $query->Where('id',$westlandBranchID);
                                                    });
                                                }
                                            }else{
                                                $q->whereHas('branch', function($q) use ($request)  {
                                                    $q->where('id','like','%'.$request->branch.'%');
                                                });
                                            }
                                        })
                                        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                            $q->whereHas('brand', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->brand.'%');
                                            });
                                        })
                                        ->whereHas('answer', function($q){
                                            $q->where('answer','like','%YES%');
                                        })
                                        ->count();
 
                 $question_eight_no    = Report::where('question_priority',8)
                                        ->whereHas('member', function($q) use ($id){
                                            $q->where('is_complete',true);
                                            $q->where('campaign_id', $id);
                                        })
                                        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                            $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                        })
                                        ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                            if($branchRequestID == $westlandBranchID){
                                                if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                    $q->whereHas('branch', function($query) use ($request)  {
                                                        $query->where('id','like','%'.$request->branch.'%');
                                                    });
                                                    $q->whereHas('brand', function($query) {
                                                        $query->where('slug','toyota');
                                                    });
                                                }else{
                                                    $q->whereHas('branch', function($q) {
                                                        $q->whereRaw("true = false");
                                                    });
                                                }
                                            }elseif($branchRequestID == $kisumuBranchID){
                                                if(empty($brandRequest)){ 
                                                    $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                        $query->where('id',$westlandBranchID);
                                                        $query->orWhere('id',$kisumuBranchID);
                                                    });
                                                }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                    $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                        $query->Where('id',$kisumuBranchID);
                                                    });
                                                }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                    $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                        $query->Where('id',$westlandBranchID);
                                                    });
                                                }
                                            }else{
                                                $q->whereHas('branch', function($q) use ($request)  {
                                                    $q->where('id','like','%'.$request->branch.'%');
                                                });
                                            }
                                        })
                                        ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                            $q->whereHas('brand', function($q) use ($request)  {
                                                $q->where('id','like','%'.$request->brand.'%');
                                            });
                                        })
                                        ->whereHas('answer', function($q){
                                            $q->where('answer','like','%NO%');
                                        })
                                        ->count();
 
                 if($campaign->slug == 'sales'){
                     /**
                      * Question No 9
                      */
                     $question_nine_yes   = Report::where('question_priority',9)
                                            ->whereHas('member', function($q) use ($id){
                                                $q->where('is_complete',true);
                                                $q->where('campaign_id', $id);
                                            })
                                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                            })
                                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                                if($branchRequestID == $westlandBranchID){
                                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                        $q->whereHas('branch', function($query) use ($request)  {
                                                            $query->where('id','like','%'.$request->branch.'%');
                                                        });
                                                        $q->whereHas('brand', function($query) {
                                                            $query->where('slug','toyota');
                                                        });
                                                    }else{
                                                        $q->whereHas('branch', function($q) {
                                                            $q->whereRaw("true = false");
                                                        });
                                                    }
                                                }elseif($branchRequestID == $kisumuBranchID){
                                                    if(empty($brandRequest)){ 
                                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                            $query->where('id',$westlandBranchID);
                                                            $query->orWhere('id',$kisumuBranchID);
                                                        });
                                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                            $query->Where('id',$kisumuBranchID);
                                                        });
                                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                            $query->Where('id',$westlandBranchID);
                                                        });
                                                    }
                                                }else{
                                                    $q->whereHas('branch', function($q) use ($request)  {
                                                        $q->where('id','like','%'.$request->branch.'%');
                                                    });
                                                }
                                            })
                                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                                $q->whereHas('brand', function($q) use ($request)  {
                                                    $q->where('id','like','%'.$request->brand.'%');
                                                });
                                            })
                                            ->whereHas('answer', function($q){
                                                $q->where('answer','like','%YES%');
                                            })
                                            ->count();
                                         
                     $question_nine_no    = Report::where('question_priority',9)
                                            ->whereHas('member', function($q) use ($id){
                                                $q->where('is_complete',true);
                                                $q->where('campaign_id', $id);
                                            })
                                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                                $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                                            })
                                            ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                                                if($branchRequestID == $westlandBranchID){
                                                    if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                                                        $q->whereHas('branch', function($query) use ($request)  {
                                                            $query->where('id','like','%'.$request->branch.'%');
                                                        });
                                                        $q->whereHas('brand', function($query) {
                                                            $query->where('slug','toyota');
                                                        });
                                                    }else{
                                                        $q->whereHas('branch', function($q) {
                                                            $q->whereRaw("true = false");
                                                        });
                                                    }
                                                }elseif($branchRequestID == $kisumuBranchID){
                                                    if(empty($brandRequest)){ 
                                                        $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                                            $query->where('id',$westlandBranchID);
                                                            $query->orWhere('id',$kisumuBranchID);
                                                        });
                                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                                                        $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                                            $query->Where('id',$kisumuBranchID);
                                                        });
                                                    }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                                                        $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                                            $query->Where('id',$westlandBranchID);
                                                        });
                                                    }
                                                }else{
                                                    $q->whereHas('branch', function($q) use ($request)  {
                                                        $q->where('id','like','%'.$request->branch.'%');
                                                    });
                                                }
                                            })
                                            ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                                                $q->whereHas('brand', function($q) use ($request)  {
                                                    $q->where('id','like','%'.$request->brand.'%');
                                                });
                                            })
                                            ->whereHas('answer', function($q){
                                                $q->where('answer','like','%NO%');
                                            })
                                            ->count();
                 }
             }
 
             if($campaign->slug == 'sales'){
                 array_push($salesQuestionsCount['yes'], $question_six_yes, $question_seven_yes, $question_eight_yes, $question_nine_yes);
 
                 array_push($salesQuestionsCount['no'], $question_six_no, $question_seven_no, $question_eight_no, $question_nine_no);
             }
             if($campaign->slug == 'parts'){
                 array_push($partsQuestionsCount['yes'], $question_six_yes, $question_seven_yes, $question_eight_yes);
 
                 array_push($partsQuestionsCount['no'], $question_six_no, $question_seven_no, $question_eight_no);
             }
             if($campaign->slug == 'body-shop'){
                 array_push($BPQuestionsCount['yes'], $question_six_yes, $question_seven_yes);
 
                 array_push($BPQuestionsCount['no'], $question_six_no, $question_seven_no);
             }
             if($campaign->slug == 'service'){
                 array_push($servicesQuestionsCount['yes'], $question_six_yes, $question_seven_yes);
 
                 array_push($servicesQuestionsCount['no'], $question_six_no, $question_seven_no);
             }
         }
 
         return view('reports.view.overall-csi',[
             'salesQuestionsCount'       => $salesQuestionsCount,
             'salesQuestions'            => $salesQuestions,
             'partsQuestionsCount'       => $partsQuestionsCount,
             'partsQuestions'            => $partsQuestions,
             'BPQuestionsCount'          => $BPQuestionsCount,
             'BPQuestions'               => $BPQuestions,
             'servicesQuestionsCount'    => $servicesQuestionsCount,
             'servicesQuestions'         => $servicesQuestions,
             'branches'                  => $branches,
             'brands'                    => $brands,
         ]);
    }

    public function npsCall(Request $request){
        $request->flash();
        $branches=Branch::all();
        $brands=Brand::all();
        $detractorKeys  = [0,1,2,3,4,5,6];
        $passiveKeys    = [7,8];
        $promoterKeys   = [9,10];
        $campaigns      = Campaign::all()->unique('slug')->values();
        $nps            =   [
            'detractor'     => [
                'sales'     => '',
                'parts'     => '',
                'body-shop' => '',
                'service'   => '',
            ],
            'passive'     => [
                'sales'     => '',
                'parts'     => '',
                'body-shop' => '',
                'service'   => '',
            ],
            'promoter'     => [
                'sales'     => '',
                'parts'     => '',
                'body-shop' => '',
                'service'   => '',
            ],
        ];

        // fixing the westland and kismu branch bug from backend because cant change the database
        $westlandBranchID       = Branch::select('id')->where('slug', 'westlands')->first()->id;
        $kisumuBranchID         = Branch::select('id')->where('slug', 'kisumu')->first()->id;
        $branchRequestID        = false;
        $brandRequest           = false;
        if($request->has('branch') && !empty($request->branch)){
            $branchRequestID    = Branch::select('id')->where('id', $request->branch)->first()->id;
            if($request->has('brand') && !empty($request->brand)){
                $brandRequest   = Brand::where('id',$request->brand)->first();
            }
        }

        foreach ($campaigns as $campaign) {
            $id = $campaign->id;

            $detractors = Report::where('question_priority',4)
                ->whereHas('member', function($q) use ($id){
                    $q->where('is_complete',true);
                    $q->where('campaign_id', $id);
                })
                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                })
                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                    if($branchRequestID == $westlandBranchID){
                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                            $q->whereHas('branch', function($query) use ($request)  {
                                $query->where('id','like','%'.$request->branch.'%');
                            });
                            $q->whereHas('brand', function($query) {
                                $query->where('slug','toyota');
                            });
                        }else{
                            $q->whereHas('branch', function($q) {
                                $q->whereRaw("true = false");
                            });
                        }
                    }elseif($branchRequestID == $kisumuBranchID){
                        if(empty($brandRequest)){ 
                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                $query->where('id',$westlandBranchID);
                                $query->orWhere('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                $query->Where('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                $query->Where('id',$westlandBranchID);
                            });
                        }
                    }else{
                        $q->whereHas('branch', function($q) use ($request)  {
                            $q->where('id','like','%'.$request->branch.'%');
                        });
                    }
                })
                ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                    $q->whereHas('brand', function($q) use ($request)  {
                        $q->where('id','like','%'.$request->brand.'%');
                    });
                })
                ->whereHas('answer', function($q) use ($detractorKeys){
                    $q->whereIn('answer', $detractorKeys);
                })
                ->count();

            $passive = Report::where('question_priority',4)
                ->whereHas('member', function($q) use ($id){
                    $q->where('is_complete',true);
                    $q->where('campaign_id', $id);
                })
                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                })
                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                    if($branchRequestID == $westlandBranchID){
                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                            $q->whereHas('branch', function($query) use ($request)  {
                                $query->where('id','like','%'.$request->branch.'%');
                            });
                            $q->whereHas('brand', function($query) {
                                $query->where('slug','toyota');
                            });
                        }else{
                            $q->whereHas('branch', function($q) {
                                $q->whereRaw("true = false");
                            });
                        }
                    }elseif($branchRequestID == $kisumuBranchID){
                        if(empty($brandRequest)){ 
                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                $query->where('id',$westlandBranchID);
                                $query->orWhere('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                $query->Where('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                $query->Where('id',$westlandBranchID);
                            });
                        }
                    }else{
                        $q->whereHas('branch', function($q) use ($request)  {
                            $q->where('id','like','%'.$request->branch.'%');
                        });
                    }
                })
                ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                    $q->whereHas('brand', function($q) use ($request)  {
                        $q->where('id','like','%'.$request->brand.'%');
                    });
                })
                ->whereHas('answer', function($q) use ($passiveKeys){
                    $q->whereIn('answer', $passiveKeys);
                })
                ->count();

            $promoter = Report::where('question_priority',4)
                ->whereHas('member', function($q) use ($id){
                    $q->where('is_complete',true);
                    $q->where('campaign_id', $id);
                })
                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                    $q->whereDate('updated_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('updated_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                })
                ->when(($request->has('branch') && !empty($request->branch)), function($q) use($request, $westlandBranchID, $kisumuBranchID, $branchRequestID, $brandRequest){
                    if($branchRequestID == $westlandBranchID){
                        if(empty($brandRequest) || (isset($brandRequest->slug) && $brandRequest->slug == 'toyota') ){ 
                            $q->whereHas('branch', function($query) use ($request)  {
                                $query->where('id','like','%'.$request->branch.'%');
                            });
                            $q->whereHas('brand', function($query) {
                                $query->where('slug','toyota');
                            });
                        }else{
                            $q->whereHas('branch', function($q) {
                                $q->whereRaw("true = false");
                            });
                        }
                    }elseif($branchRequestID == $kisumuBranchID){
                        if(empty($brandRequest)){ 
                            $q->whereHas('branch', function($query) use ($westlandBranchID, $kisumuBranchID)  {
                                $query->where('id',$westlandBranchID);
                                $query->orWhere('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug == 'toyota'){
                            $q->whereHas('branch', function($query) use ($kisumuBranchID)  {
                                $query->Where('id',$kisumuBranchID);
                            });
                        }elseif(isset($brandRequest->slug) && $brandRequest->slug != 'toyota'){
                            $q->whereHas('branch', function($query) use ($westlandBranchID)  {
                                $query->Where('id',$westlandBranchID);
                            });
                        }
                    }else{
                        $q->whereHas('branch', function($q) use ($request)  {
                            $q->where('id','like','%'.$request->branch.'%');
                        });
                    }
                })
                ->when(($request->has('brand') && !empty($request->brand)), function($q) use($request){
                    $q->whereHas('brand', function($q) use ($request)  {
                        $q->where('id','like','%'.$request->brand.'%');
                    });
                })
                ->whereHas('answer', function($q) use ($promoterKeys){
                    $q->whereIn('answer', $promoterKeys);
                })
                ->count();

            if(!empty($campaign->slug) && $campaign->slug == 'sales'){
                $nps['detractor']['sales']  = $detractors;
                $nps['passive']['sales']    = $passive;
                $nps['promoter']['sales']   = $promoter;
            }elseif(!empty($campaign->slug) && $campaign->slug == 'parts'){
                $nps['detractor']['parts']  = $detractors;
                $nps['passive']['parts']    = $passive;
                $nps['promoter']['parts']   = $promoter;
            }elseif(!empty($campaign->slug) && $campaign->slug == 'body-shop'){
                $nps['detractor']['body-shop']  = $detractors;
                $nps['passive']['body-shop']    = $passive;
                $nps['promoter']['body-shop']   = $promoter;
            }elseif(!empty($campaign->slug) && $campaign->slug == 'service'){
                $nps['detractor']['service']  = $detractors;
                $nps['passive']['service']    = $passive;
                $nps['promoter']['service']   = $promoter;
            }
        }

        return view('reports.view.nps-score',[
            'nps'           => $nps,
            'branches'      => $branches,
            'brands'        => $brands,
            'campaigns'     => $campaigns,
        ]);
    }
}
