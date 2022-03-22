<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\Report;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class NPSScoreReportExport implements FromCollection,ShouldAutoSize, WithStrictNullComparison
{
    public function  __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $request    = $this->request;
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


        foreach ($campaigns as $campaign) {
            $id = $campaign->id;

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

        $exportedData = collect();

        $exportedCampaignI = 0;
        foreach ($campaigns as $campaign){

            $singleArrayCampaign = [];
            /**
             * Heading Logic
             */
            if($exportedCampaignI != 0){
                array_push($singleArrayCampaign, '');

                $exportedData->push($singleArrayCampaign);

                $singleArrayCampaign = [];
            }

            array_push($singleArrayCampaign, $campaign->name . ' NPS Score');

            $exportedData->push($singleArrayCampaign);

            $singleArrayCampaign = [];

            /**
             * Promoter Logic
             */
            array_push($singleArrayCampaign, 'Promoter');

            array_push($singleArrayCampaign, $nps['promoter'][$campaign->slug]);

            $total = $nps['detractor'][$campaign->slug] + $nps['passive'][$campaign->slug] + $nps['promoter'][$campaign->slug];
            array_push($singleArrayCampaign, $total);

            if(empty($total)){
                $indivualPercentagePromoter = 0.0;
            }else{
                $indivualPercentagePromoter = round($nps['promoter'][$campaign->slug]/$total * 100, 1);
            }
            array_push($singleArrayCampaign, $indivualPercentagePromoter);

            if(empty($total)){
                $totalPercentage = 0.0;
            }else{
                $totalPercentage = round((($nps['promoter'][$campaign->slug]/$total)*100)-(($nps['detractor'][$campaign->slug]/$total)*100), 1);
            }
            array_push($singleArrayCampaign, $totalPercentage);

            $exportedData->push($singleArrayCampaign);
            $singleArrayCampaign = [];

            /**
             * Passive Logic
             */
            array_push($singleArrayCampaign, 'Passive');

            array_push($singleArrayCampaign, $nps['passive'][$campaign->slug]);
            
            array_push($singleArrayCampaign, ' ');

            if(empty($total)){
                $indivualPercentagePassive = 0.0;
            }else{
                $indivualPercentagePassive = round($nps['passive'][$campaign->slug]/$total * 100, 1);
            }
            array_push($singleArrayCampaign, $indivualPercentagePassive);

            array_push($singleArrayCampaign, ' ');

            $exportedData->push($singleArrayCampaign);
            $singleArrayCampaign = [];

            /**
             * Detractor Logic
             */
            array_push($singleArrayCampaign, 'Detractor');

            array_push($singleArrayCampaign, $nps['detractor'][$campaign->slug]);

            array_push($singleArrayCampaign, ' ');

            if(empty($total)){
                $indivualPercentageDetractor = 0.0;
            }else{
                $indivualPercentageDetractor = round($nps['detractor'][$campaign->slug]/$total * 100, 1);
            }
            array_push($singleArrayCampaign, $indivualPercentageDetractor);

            array_push($singleArrayCampaign, ' ');

            $exportedData->push($singleArrayCampaign);

            $exportedCampaignI++;
        }

        return $exportedData;
    }
}
