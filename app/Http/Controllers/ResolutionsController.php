<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Resolution;
use App\Models\ToyotaCase;
use Illuminate\Http\Request;
use App\Models\ClassificationType;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResolutionRateExport;
use Illuminate\Pagination\LengthAwarePaginator;

class ResolutionsController extends Controller
{

    //secret = $2y$10$Ksl.dw6M174NUcf9gxy4U.cUs01DUBGWFGHUiNAFJdyg2Xr.hFGv2
    public function __construct(){

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Response|object
     */
    public function index(Request $request)
    {
        $request->flash();

        $campaigns=Campaign::all();
        $branches=Branch::all();
        $brands=Brand::all();
        $feedbacks=ClassificationType::all();
        $advisors=User::with(['role'])->whereHas('role', function($q){
            $q->where('slug','champion')->orWhere('slug','advisor');
        })->get();

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
                $resolutions[] =array(
                    'name' => $recordUserName,
                    'department'=>$recordCampaignName,
                    'branch'=>$recordBranchName,
                    'brand'=>$recordBrandName,
                    'total_cases'=>$totalCases,
                    'open_cases'=>$openCases,
                    'closed_cases'=>$closedCases,
                    'resolution_rate'=> round(($closedCases/$totalCases) * 100) .'%',
                );
            }
            
        }

        // $total=count($resolutions);
        // $per_page = 15;
        // $current_page = $request->input("page") ?? 1;

        // $starting_point = ($current_page * $per_page) - $per_page;

        // $resolutions = array_slice($resolutions, $starting_point, $per_page, true);
        
        // $resolutions = new LengthAwarePaginator($resolutions, $total, $per_page, $current_page, [
        //     'path' => $request->url(),
        //     'query' => $request->query(),
        // ]);

        return view('customer.resolutions',[
            'campaigns'     => $campaigns,
            'branches'      => $branches,
            'brands'        => $brands,
            'feedbacks'     => $feedbacks,
            'advisors'      => $advisors,
            'resolutions'   => $resolutions,
        ]);
    }
    
    public function export(Request $request){
        ini_set('max_execution_time', 3000);

        return Excel::download(new ResolutionRateExport($request), 'ResolutionRateExport.xlsx');
    }

     /**
      * Show
      */
       /**
     * Display the specified resource.
     *
     * @param Resolution $resolution
     * @return JsonResponse|Response|object
     */
    public function show(Resolution $resolution)
    {
        return $this->successResponse(
            $resolution->load(
                'user',
                'campaign',
                'branch',
                'brand'
            )
        );
    }

}
