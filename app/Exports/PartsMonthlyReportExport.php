<?php

namespace App\Exports;

use Auth;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartsMonthlyReportExport implements FromCollection,ShouldAutoSize,WithHeadings
{   
    public function  __construct($request,$id)
    {
        $this->request= $request;
        $this->id= $id;
    }

    public function headings(): array
    {
        return [
            'Date Of Delivery',
            'Customer',
            'Customer Name',
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
            'Q. 4 I am making a follow up to get your satisfaction with regards to your experience when you came to purchase parts from us. On a scale of 0-10 (0=Very Unlikely, 10=Very Likely) - How likely is it that you would recommend Toyota Kenya to family, friends, colleagues?',	
            'Q. 5 Please tell us why you gave this score?',
            'Category (NPS VOC)',
            'Q. 6 Were you satisfied with the process from documentation up to supply of parts?',
            'Q. 7 Were the correct parts supplied?',
            'Q. 8 Did we have all the parts you required?',
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
            'Kindly confirm if you are aware of our Toyota Kenya Toll Free line',
            'Whenever you pay a visit to our offices, are you satisfied with the measures put in place for COVID 19 protocols?',
            'Comment Box',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;
        $id = $this->id;

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
        //
        $records=Member::with([
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
        ->get();

        $exportedData =  collect();

        foreach($records as $record){
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

            $singleRecord = [];
            array_push($singleRecord, $record->contact->date_of_delivery);
            array_push($singleRecord, $record->contact->customer);
            array_push($singleRecord, $record->contact->customer_description);
            array_push($singleRecord, $record->contact->telephone_one);
            array_push($singleRecord, $record->contact->cust_classification);
            array_push($singleRecord, $record->contact->license_plate_number);
            array_push($singleRecord, $record->contact->vin_number);
            array_push($singleRecord, $record->contact->vehicle_model);
            array_push($singleRecord, $record->contact->new_used_vehicle);
            array_push($singleRecord, isset($record->contact->created_by) ? \App\Models\User::query()->firstWhere('pf_no',$record->contact->created_by)->name : '');
            array_push($singleRecord, isset($branch) ? $branch : '');
            array_push($singleRecord, isset($record->brand->name) ? $record->brand->name : '');
            array_push($singleRecord, $record->contact->reason_for_visit);
            array_push($singleRecord, $record->contact->order_number);
            array_push($singleRecord, $record->contact->header_text);
            array_push($singleRecord, $record->contact->gate_pass_ind);
            array_push($singleRecord, $record->campaign->name);
            array_push($singleRecord, isset($q[4]) ? $q[4] : '');
            array_push($singleRecord, isset($q[5]) ? $q[5] : '');
            array_push($singleRecord, $type);
            array_push($singleRecord, isset($q[6]) ? $q[6] : '');
            array_push($singleRecord, isset($q[7]) ? $q[7] : '');
            array_push($singleRecord, isset($q[8]) ? $q[8] : '');
            array_push($singleRecord, isset($record->toyota_case) ? $record->toyota_case->voc_customer : '');
            array_push($singleRecord, isset($record->toyota_case) ? $record->toyota_case->classification_type->name: '');
            array_push($singleRecord, isset($record->toyota_case) ? $record->toyota_case->campaign->name : '');
            array_push($singleRecord, isset($record->toyota_case) ? $record->toyota_case->classification->name : '');
            array_push($singleRecord, isset($record->toyota_case) ? ucfirst(\Illuminate\Support\Str::lower($record->toyota_case->action)) : '');
            array_push($singleRecord, $is_closed);
            array_push($singleRecord, (isset($record->toyota_case) && isset($record->toyota_case->comments)) ? $record->toyota_case->comments : '');
            array_push($singleRecord, \Carbon\Carbon::parse($record->updated_at)->format('m/d/Y'));
            array_push($singleRecord, \Carbon\Carbon::parse($record->updated_at)->format('h:i a'));
            array_push($singleRecord, isset($record->user) ? $record->user->name: '');
            array_push($singleRecord, \Carbon\Carbon::parse($record->updated_at)->format('H'));
            array_push($singleRecord, $record->disposition->disposition_type->name);
            array_push($singleRecord, $record->disposition->name);
            array_push($singleRecord, $record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('m/d/Y') :'');
            array_push($singleRecord, $record->next_call_scheduled_at >= now() && $record->disposition->name === 'Call Back' ?  \Carbon\Carbon::parse($record->next_call_scheduled_at)->format('h:i a') :'');
            array_push($singleRecord, number_format($record->attempts));
            array_push($singleRecord, (isset($record->awareness_creation) && isset($record->awareness_creation->aware)) ? $record->awareness_creation->aware : '');
            array_push($singleRecord, (isset($record->awareness_creation) && isset($record->awareness_creation->satisfaction)) ? $record->awareness_creation->satisfaction : '');
            array_push($singleRecord, (isset($record->awareness_creation) && isset($record->awareness_creation->comment)) ? $record->awareness_creation->comment : '');

            $exportedData->push($singleRecord);
        }

        return $exportedData;

    }
}
