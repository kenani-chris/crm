<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\ToyotaCase;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ResolutionRateExport implements FromCollection,ShouldAutoSize,WithHeadings
{
    public function  __construct($request)
    {
        $this->request= $request;
    }

    public function headings(): array
    {
        return [
            // '#',
            'Advisor',
            'Department',
            'Branch',
            'Brand',
            'Total Cases',
            'Open Cases',
            'Closed Cases',
            'Resolution Rate',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;

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

        $records=ToyotaCase::where('is_negative',true)
                ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                    $q->whereDate('created_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('created_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                })
                ->when(Auth::user()->role->slug=='advisor' || Auth::user()->role->slug=='champion', function($q){
                    $q->whereHas( 'user', function($q) {
                        $q->where('id', Auth::user()->id);
                    });
                })
                ->when((!empty($request->campaign)), function($q) use($request){
                    $q->with('campaign')->whereHas('campaign', function($query) use($request){
                        $query->where('id','ILIKE','%'.$request->campaign.'%');
                    });
                })
                ->when((!empty($request->branch)), function($q) use($request){
                    $q->with('branch')->whereHas('branch', function($query) use($request){
                        $query->where('id','ILIKE','%'.$request->branch.'%');
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
                ->when((!empty($request->brand)), function($q) use($request){
                    $q->with('brand')->whereHas('brand', function($query) use($request){
                        $query->where('id','ILIKE','%'.$request->brand.'%');
                    });
                })
                ->get(['branch_id','brand_id','campaign_id','member_id','is_closed','is_negative','created_at', 'comments'])
                ->groupBy(['member.contact.created_by', 'brand_id']);

        $resolutions = array();
        // $resolutionsI = 0;

        foreach($records as $contactCreatedBy => $userBrands){
            if(!empty($request->advisor)){
                $filterUserName = User::findOrFail($request->advisor)->pf_no;
                if($filterUserName != $contactCreatedBy){
                    continue;
                }
            }
            
            $recordUserName = (isset(User::where('pf_no', $contactCreatedBy)->first()->name)) ? User::where('pf_no', $contactCreatedBy)->first()->name : 'N/A';
            foreach($userBrands as $userBrandID => $allRecords){
                $totalCases = 0;
                $openCases = 0;
                $closedCases = 0;
                $recordCampaignName = '';
                $recordBranchName = '';
                $recordBrandName = '';

                
                foreach($allRecords as $record){
                    if($totalCases == 0){
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
                        $recordCampaignName = $record->campaign->name;
                        $recordBranchName = $branch;
                        $recordBrandName = $record->brand->name;
                    }
                    $totalCases++;
                    if(!empty($record->comments)){
                        $closedCases++;
                    }else{
                        $openCases++;
                    }
                }
                // $resolutionsI++;
                $resolutions[] =array(
                    // $resolutionsI,
                    $recordUserName,
                    $recordCampaignName,
                    $recordBranchName,
                    $recordBrandName,
                    (int)$totalCases,
                    (int)$openCases,
                    (int)$closedCases,
                    round(($closedCases/$totalCases) * 100) .'%',
                );
            }
            
        }


        return collect($resolutions);
    }
}
