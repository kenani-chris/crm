<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\Report;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OverallCSIReportExport implements FromCollection,ShouldAutoSize,WithHeadings
{
    public function  __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            '  ',
            'CSI Performance',
            'Yes',
            'No',
            'N/C',
            'Hit Rate',
            'Result',
            'CSI Score',
        ];
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request    = $this->request;

        $campaigns = Campaign::all()->unique('slug')->values();
        $branches = Branch::all();
        $brands = Brand::all();
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

        $exportedData = collect();

        $salesQuestionsCountI = 0;
        foreach ($salesQuestionsCount['yes'] as $salesQuestionsCountSingle){
            $singleArraySales = [];
            if (empty($salesQuestionsCountSingle)){
                $resultSales = '0%';
            }else{
                $resultSales = round($salesQuestionsCountSingle / ($salesQuestionsCountSingle + $salesQuestionsCount['no'][$salesQuestionsCountI]) * 100) . '%' ;
            }

            if (empty(array_sum($salesQuestionsCount['yes']))){
                $csiSales = '0%';
            }else{
                $csiSales = round(array_sum($salesQuestionsCount['yes']) / (array_sum($salesQuestionsCount['yes']) + array_sum($salesQuestionsCount['no'])) * 100) . '%';
            }

            array_push($singleArraySales, ($salesQuestionsCountI == 0) ? 'Sales CSI' : '');
            array_push($singleArraySales, $salesQuestions[$salesQuestionsCountI]);
            array_push($singleArraySales, $salesQuestionsCountSingle);
            array_push($singleArraySales, $salesQuestionsCount['no'][$salesQuestionsCountI]);
            array_push($singleArraySales, 0);
            array_push($singleArraySales, '');
            array_push($singleArraySales, $resultSales);
            array_push($singleArraySales, ($salesQuestionsCountI == 0) ? $csiSales : '');

            $exportedData->push($singleArraySales);
            $salesQuestionsCountI++;
        }

        $partsQuestionsCountI = 0;
        foreach ($partsQuestionsCount['yes'] as $partsQuestionsCountSingle){
            $singleArrayParts = [];
            if (empty($partsQuestionsCountSingle)){
                $resultParts = '0%';
            }else{
                $resultParts = round($partsQuestionsCountSingle / ($partsQuestionsCountSingle + $partsQuestionsCount['no'][$partsQuestionsCountI]) * 100) . '%' ;
            }

            if (empty(array_sum($partsQuestionsCount['yes']))){
                $csiParts = '0%';
            }else{
                $csiParts = round(array_sum($partsQuestionsCount['yes']) / (array_sum($partsQuestionsCount['yes']) + array_sum($partsQuestionsCount['no'])) * 100) . '%';
            }

            array_push($singleArrayParts, ($partsQuestionsCountI == 0) ? 'Parts CSI' : '');
            array_push($singleArrayParts, $partsQuestions[$partsQuestionsCountI]);
            array_push($singleArrayParts, $partsQuestionsCountSingle);
            array_push($singleArrayParts, $partsQuestionsCount['no'][$partsQuestionsCountI]);
            array_push($singleArrayParts, 0);
            array_push($singleArrayParts, '');
            array_push($singleArrayParts, $resultParts);
            array_push($singleArrayParts, ($partsQuestionsCountI == 0) ? $csiParts : '');

            $exportedData->push($singleArrayParts);
            $partsQuestionsCountI++;
        }

        $servicesQuestionsCountI = 0;
        foreach ($servicesQuestionsCount['yes'] as $servicesQuestionsCountSingle){
            $singleArrayServices = [];
            if (empty($servicesQuestionsCountSingle)){
                $resultServices = '0%';
            }else{
                $resultServices = round($servicesQuestionsCountSingle / ($servicesQuestionsCountSingle + $servicesQuestionsCount['no'][$servicesQuestionsCountI]) * 100) . '%' ;
            }

            if (empty(array_sum($servicesQuestionsCount['yes']))){
                $csiServices = '0%';
            }else{
                $csiServices = round(array_sum($servicesQuestionsCount['yes']) / (array_sum($servicesQuestionsCount['yes']) + array_sum($servicesQuestionsCount['no'])) * 100) . '%';
            }

            array_push($singleArrayServices, ($servicesQuestionsCountI == 0) ? 'Service CSI' : '');
            array_push($singleArrayServices, $servicesQuestions[$servicesQuestionsCountI]);
            array_push($singleArrayServices, $servicesQuestionsCountSingle);
            array_push($singleArrayServices, $servicesQuestionsCount['no'][$servicesQuestionsCountI]);
            array_push($singleArrayServices, 0);
            array_push($singleArrayServices, '');
            array_push($singleArrayServices, $resultServices);
            array_push($singleArrayServices, ($servicesQuestionsCountI == 0) ? $csiServices : '');

            $exportedData->push($singleArrayServices);
            $servicesQuestionsCountI++;
        }

        $BPQuestionsCountI = 0;
        foreach ($BPQuestionsCount['yes'] as $BPQuestionsCountSingle){
            $singleArrayBP = [];
            if (empty($BPQuestionsCountSingle)){
                $resultBP = '0%';
            }else{
                $resultBP = round($BPQuestionsCountSingle / ($BPQuestionsCountSingle + $BPQuestionsCount['no'][$BPQuestionsCountI]) * 100) . '%' ;
            }

            if (empty(array_sum($BPQuestionsCount['yes']))){
                $csiBP = '0%';
            }else{
                $csiBP = round(array_sum($BPQuestionsCount['yes']) / (array_sum($BPQuestionsCount['yes']) + array_sum($BPQuestionsCount['no'])) * 100) . '%';
            }

            array_push($singleArrayBP, ($BPQuestionsCountI == 0) ? 'BP CSI' : '');
            array_push($singleArrayBP, $BPQuestions[$BPQuestionsCountI]);
            array_push($singleArrayBP, $BPQuestionsCountSingle);
            array_push($singleArrayBP, $BPQuestionsCount['no'][$BPQuestionsCountI]);
            array_push($singleArrayBP, 0);
            array_push($singleArrayBP, '');
            array_push($singleArrayBP, $resultBP);
            array_push($singleArrayBP, ($BPQuestionsCountI == 0) ? $csiBP : '');

            $exportedData->push($singleArrayBP);
            $BPQuestionsCountI++;
        }

        

        return collect($exportedData);
    }
    
}
