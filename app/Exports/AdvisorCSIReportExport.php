<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Report;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdvisorCSIReportExport implements FromCollection,ShouldAutoSize,WithHeadings
{
    public function  __construct($request,$id)
    {
        $this->request= $request;
        $this->id= $id;
    }

    public function headings(): array
    {
        $id         = $this->id;
        $campaign   = Campaign::findOrFail($id);

        if(!empty($campaign->slug)){
            if($campaign->slug == 'sales'){
                return [
                    'No.',
                    'Service Advisor',
                    'Branch',
                    'Yes',
                    'No',
                    'Yes',
                    'No',
                    'Yes',
                    'No',
                    'Yes',
                    'No',
                    'Total Yes',
                    'Total No',
                    'Total',
                    'CSI Score',
                ];
            }else{
                return [
                    'No.',
                    'Service Advisor',
                    'Branch',
                    'Yes',
                    'No',
                    'Yes',
                    'No',
                    'Total Yes',
                    'Total No',
                    'Total',
                    'CSI Score',
                ];
            }
        }
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request    = $this->request;
        $id         = $this->id;
        $campaign   = Campaign::findOrFail($id);
        

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
        
        $records    = array();
    
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

        return collect($data_arr);
    }
}
