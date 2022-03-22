<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ticket;
use App\User;
use DB;
use Carbon\Carbon;
use App\CatalogWCW;
use App\Catalog;
use App\CatalogAnalyst;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */








    public function index()

    {

        $greet=$this->greet();

       return view('home',['greet'=>$greet]);
    }

    public function dashboard(Request $request){

        if($request->ajax())
        {

            $dateleo=date('Y-m-d');

            if($request->date_from != '' && $request->date_to != '')
             {
                //Analyst
                if(Auth::user()->level=="Analyst" || Auth::user()->level=="Swing Capacity"){
                    $this->analystDashboard($request->date_from,$request->date_to);
                }
                //End Analyst
                //Team Lead
                else if(Auth::user()->level=="Supervisor"){
                    $this->teamLeadDashboard($request->date_from,$request->date_to);
                }
                //End team Lead

                //WFC and Admin
                else if(Auth::user()->level=="WFC" || Auth::user()->level=="Admin"){

                    $this->wfcDashboard($request->date_from,$request->date_to);

                }else{

                }
                //WFC and Admin
             }
             else{

             //Analyst
             if(Auth::user()->level=="Analyst" || Auth::user()->level=="Swing Capacity"){
                $this->analystDashboard($dateleo, Carbon::now()->add(1,'day'));
              }
            //End Analyst
            //Team Lead
            else if(Auth::user()->level=="Supervisor"){
                $this->teamLeadDashboard($dateleo, Carbon::now()->add(1,'day'));
            }
            //End team Lead

            //WFC and Admin
           else if(Auth::user()->level=="WFC" || Auth::user()->level=="Admin"){
            $this->wfcDashboard($dateleo, Carbon::now()->add(1,'day'));
            }
            else{

            }
            //WFC and Admin


             }

        }
    }


    public function analystDashboard($date_from, $date_to){
        $dashboard= CatalogAnalyst::whereDate('updated_at', '>=',$date_from)
        ->whereDate('updated_at', '<=',$date_to)
        ->where('analyst_id', Auth::user()->id)
        ->selectRaw("count(case when catalog_status = 'Assigned' then 1 end) as catalogAssigned")
        ->selectRaw("count(case when catalog_status= 'In Progress' then 1 end) as catalogInProgress")
        ->selectRaw("count(case when catalog_status= 'Supervisor Pending' then 1 end) as catalogInReview")
        ->selectRaw("count(case when catalog_status= 'Revision' then 1 end) as catalogRevision")
        ->selectRaw("count(case when catalog_status= 'Stalled' then 1 end) as catalogStalled")
        ->selectRaw("count(case when catalog_status= 'Analyst_Req_Stall' then 1 end) as catalogStalledRequest")
        ->first(); 
        $dashboard['sku_assigned'] = $dashboard->where('analyst_id', Auth::user()->id)->whereDate('created_at', '>=',$date_from)->whereDate('created_at', '<=',$date_to)->sum('analyst_sku_assigned');
        $dashboard['sku_owner'] = $dashboard->where('analyst_id', Auth::user()->id)->where('catalog_status','Completed')->whereDate('created_at', '>=',$date_from)->whereDate('created_at', '<=',$date_to)->sum('analyst_sku_count');
        $dashboard['catalogTLAssigned']="";
        $dashboard['catalogPending']="";
        $dashboard['catalogReceived']="";
        $dashboard['catalogActivated']='';
        $dashboard['catalogCompleted']=CatalogAnalyst::whereDate('supervisor_completed_date', '>=',$date_from)->whereDate('supervisor_completed_date', '<=',$date_to)->where('analyst_id', Auth::user()->id)->count('id');

        $dashboard['complete_catalogs'] = $dashboard->where('analyst_id', Auth::user()->id)->where('catalog_status','Completed')->whereDate('supervisor_completed_date', '>=',$date_from)->whereDate('supervisor_completed_date', '<=',$date_to)->count('id');


        echo json_encode($dashboard);
    }

    public function teamLeadDashboard($date_from, $date_to){

        $dashboard= CatalogWCW::whereDate('updated_at', '>=',$date_from)
        ->whereDate('updated_at', '<=',$date_to)
        ->where('supervisor_id', Auth::user()->id)
        ->selectRaw("count(case when catalog_status = 'Assigned' then 1 end) as catalogAssigned")
        ->selectRaw("count(case when catalog_status = 'TL_Assigned' then 1 end) as catalogTLAssigned")
        ->selectRaw("count(case when catalog_status= 'In Progress' then 1 end) as catalogInProgress")
        ->selectRaw("count(case when catalog_status= 'Supervisor Pending' then 1 end) as catalogInReview")
        ->selectRaw("count(case when catalog_status= 'Revision' then 1 end) as catalogRevision")
    
        ->selectRaw("count(case when catalog_status= 'Stalled' then 1 end) as catalogStalled")
        ->first(); 
        $dashboard['sku_assigned'] = $dashboard->where('supervisor_id', Auth::user()->id)->whereDate('created_at', '>=',$date_from)
        ->whereDate('created_at', '<=',$date_to)->sum('supervisor_sku_assigned');
        $dashboard['sku_owner'] ="";
        $dashboard['catalogPending']="";
        $dashboard['catalogReceived']="";
        $dashboard['catalogActivated']="";
        $dashboard['catalogCompleted']=CatalogAnalyst::whereDate('supervisor_completed_date', '>=',$date_from)
        ->whereDate('supervisor_completed_date', '<=',$date_to)->where('supervisor_id', Auth::user()->id)->count('id');

        $dashboard['catalogStalledRequest']=CatalogAnalyst::where('user_id', Auth::user()->id)->where('catalog_status','Analyst_Req_Stall')
        ->whereDate('created_at', '>=',$date_from)->whereDate('created_at', '<=',$date_to)->count('id');

        $dashboard['complete_catalogs'] = $dashboard->where('supervisor_id', Auth::user()->id)->where('catalog_status','Completed')
        ->whereDate('supervisor_completed_date', '>=',$date_from)->whereDate('supervisor_completed_date', '<=',$date_to)->count('id');

        echo json_encode($dashboard);

        
    }

    public function wfcDashboard($date_from, $date_to){

        $dashboard= Catalog::whereDate('updated_at', '>=',$date_from)
        ->whereDate('updated_at', '<=',$date_to)
        ->selectRaw("count(case when status = 'Pending' then 1 end) as catalogPending")
        ->selectRaw("count(case when status = 'Assigned' then 1 end) as catalogAssigned")
        ->selectRaw("count(case when status = 'TL_Assigned' then 1 end) as catalogTLAssigned")
        ->selectRaw("count(case when status= 'In Progress' then 1 end) as catalogInProgress")
        ->selectRaw("count(case when status= 'Supervisor Pending' then 1 end) as catalogInReview")
        ->selectRaw("count(case when status= 'Revision' then 1 end) as catalogRevision")
       //->selectRaw("count(case when status= 'Completed' then 1 end) as catalogCompleted")
        ->selectRaw("count(case when status= 'Stalled' then 1 end) as catalogStalled")
        ->selectRaw("count(case when activationstatus='Activated' then 1 end) as catalogActivated")
        ->first(); 
        $dashboard['catalogCompleted']=CatalogAnalyst::whereDate('supervisor_completed_date', '>=',$date_from)
        ->whereDate('supervisor_completed_date', '<=',$date_to)->count('id');
        $dashboard['catalogReceived']=$dashboard->whereDate('created_at', '>=',$date_from)
        ->whereDate('created_at', '<=',$date_to)->count('id');
        $dashboard['sku_assigned']=CatalogWCW::whereDate('created_at', '>=',$date_from)
        ->whereDate('created_at', '<=',$date_to)->sum('catalog_sku'); 
        $dashboard['sku_owner'] =CatalogAnalyst::where('catalog_status','Completed')->whereDate('supervisor_completed_date', '>=',$date_from)
        ->whereDate('supervisor_completed_date', '<=',$date_to)->sum('analyst_sku_count');
        $dashboard['catalogStalledRequest']=CatalogWCW::where('catalog_status','Analyst_Req_Stall')
        ->whereDate('created_at', '>=',$date_from)
        ->whereDate('created_at', '<=',$date_to)->count('id');

        echo json_encode($dashboard);
    }

    public function greet()
    {
        $hour = date('H');
        if ($hour < 12) {
            return 'Good Morning';
        }
        if ($hour < 17) {
            return 'Good Afternoon';
        }
        return 'Good Evening';
    }

   


   
}
