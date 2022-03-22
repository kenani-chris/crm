<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Disposition;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        $surveys=Campaign::all();

        $branches=Branch::all();

        $brands=Brand::all();

        return view('livewire.dashboard',get_defined_vars());
    }

    /**
     * Dahsboard data
     */

     public function dashboard(Request $request){


        if(empty($request->from_date) && empty($request->to_date)){
            $to_date=date('Y-m-d', strtotime(Carbon::now()));
            $from_date=date('Y-m-d', strtotime(Carbon::now()->subDays(30)));
            $todays="Last 30 days";
        }else{
            $to_date=$request->to_date;

            $from_date=$request->from_date;

            $todays= date('d F Y', strtotime($from_date)).' to '.date('d F Y', strtotime($to_date));
        }



        return response()->json([
            'leads' => count(Contact::all()),
            'todays' =>$todays,
            'dialed' => count(Member::query()
                ->whereIn('campaign_id', $request->has('campaign_id') && !empty($request->campaign_id) ? [$request->campaign_id] : Campaign::query()->get('id')->toArray())
                ->whereIn('branch_id', $request->has('branch_id') && !empty($request->branch_id) ? [$request->branch_id] : Branch::query()->get('id')->toArray())
                ->whereIn('brand_id', $request->has('brand_id') && !empty($request->brand_id)  ? [$request->brand_id] : Brand::query()->get('id')->toArray())
                ->whereIn('user_id', request()->user()->role->level == 1 ? User::query()->get('id')->toArray() : [request()->user()->id])
                ->whereDate('updated_at', '>=',$from_date)->whereDate('updated_at', '<=',$to_date)
                ->whereNotNull('call_ended_at')
                ->get()),
            'reachable' => count(Member::query()
                ->whereIn('campaign_id', $request->has('campaign_id')  && !empty($request->campaign_id)  ? [$request->campaign_id] : Campaign::query()->get('id')->toArray())
                ->whereIn('branch_id', $request->has('branch_id')  && !empty($request->branch_id) ? [$request->branch_id] : Branch::query()->get('id')->toArray())
                ->whereIn('brand_id', $request->has('brand_id')   && !empty($request->brand_id)? [$request->brand_id] : Brand::query()->get('id')->toArray())
                ->where('is_reachable', true)
                ->whereIn('user_id', request()->user()->role->level == 1 ? User::query()->get('id')->toArray() : [request()->user()->id])
                ->whereDate('updated_at', '>=',$from_date)->whereDate('updated_at', '<=',$to_date)
                ->whereNotNull('call_ended_at')
                ->get()),
            'unreachable' => count(Member::query()
                ->whereIn('campaign_id', $request->has('campaign_id')  && !empty($request->campaign_id)  ? [$request->campaign_id] : Campaign::query()->get('id')->toArray())
                ->whereIn('branch_id', $request->has('branch_id')  && !empty($request->branch_id) ? [$request->branch_id] : Branch::query()->get('id')->toArray())
                ->whereIn('brand_id', $request->has('brand_id') && !empty($request->brand_id) ? [$request->brand_id] : Brand::query()->get('id')->toArray())
                ->where('is_reachable', false)
                ->whereIn('user_id', request()->user()->role->level == 1 ? User::query()->get('id')->toArray() : [request()->user()->id])
                ->whereDate('updated_at', '>=',$from_date)->whereDate('updated_at', '<=',$to_date)
                ->whereNotNull('call_ended_at')
                ->get()),
            'callbacks' => count(Member::query()
                ->whereIn('campaign_id', $request->has('campaign_id')  && !empty($request->campaign_id) ? [$request->campaign_id] : Campaign::query()->get('id')->toArray())
                ->whereIn('branch_id', $request->has('branch_id') && !empty($request->branch_id)  ? [$request->branch_id] : Branch::query()->get('id')->toArray())
                ->whereIn('brand_id', $request->has('brand_id') && !empty($request->brand_id) ? [$request->brand_id] : Brand::query()->get('id')->toArray())
                ->where('disposition_id', Disposition::query()
                    ->firstWhere('name', 'Call Back')->id)
                ->whereDate('next_call_scheduled_at', '>=', date('Y-m-d H:i:s', strtotime(now())))
                ->whereIn('user_id', request()->user()->role->level == 1 ? User::query()->get('id')->toArray() : [request()->user()->id])
                ->whereDate('updated_at', '>=',$from_date)->whereDate('updated_at', '<=',$to_date)
                ->get()),
            'complete' => count(Member::query()
                ->whereIn('campaign_id', $request->has('campaign_id')  && !empty($request->campaign_id) ? [$request->campaign_id] : Campaign::query()->get('id')->toArray())
                ->whereIn('branch_id', $request->has('branch_id') && !empty($request->branch_id)  ? [$request->branch_id] : Branch::query()->get('id')->toArray())
                ->whereIn('brand_id', $request->has('brand_id') && !empty($request->brand_id) ? [$request->brand_id] : Brand::query()->get('id')->toArray())
                ->whereIn('user_id', request()->user()->role->level == 1 ? User::query()->get('id')->toArray() : [request()->user()->id])
                ->where('is_complete', true)
                ->whereDate('updated_at', '>=',$from_date)->whereDate('updated_at', '<=',$to_date)
                ->get()),
            ], 200);



     }

}
