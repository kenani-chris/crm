<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BrandUser;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Brand;
use Carbon\Carbon;
use Hash;
use App\Models\Campaign;
use Illuminate\Support\Str;


class UserController extends Controller
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


    
    
    public function index(){
       
            return view('users.list');

    }

    public function show($id){

          
    $user=User::query()->with([
        'role',
        'brands',
        'campaign'
    ])->where('id',$id)->first();

    $user['branches']=Branch::all();
    $user['campaigns']=Campaign::all();
    $user['roles']=Role::all();
    $user['allbrands']=Brand::all();
    if($user){
            return view('users.edit',get_defined_vars());
    }
    else{
        return view('users.list');
    }

}


/**
 * Edit user
 */

 public function update(Request $request, $id){

    $this->validate($request, [
        'branch_id' => ['string', 'max:255', 'exists:branches,id'],
        'campaign_id' => ['string', 'max:255', 'exists:campaigns,id'],
        'role_id' => ['required', 'string', 'max:255', 'exists:roles,id'],
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'max:255'],
        'pf_no'=>['nullable','numeric'],
        'phone_number' => ['nullable', 'numeric'],
        'brand' => ['array'] 
    ]);

    $user=new User();

    /*$user->fill([
            
        ]);*/

        // delete all linked brands
        $brands = BrandUser::where('user_id',$id)->get();

        if(!empty($brands)){
        
                foreach ($brands as $brand) {
                    $brand->forceDelete();
                }
        }
        // create new brands
        foreach ($request->brand as $brand_id) {
            BrandUser::query()->updateOrCreate([
                'user_id' => $id,
                'brand_id' => $brand_id
            ]);
        }

        if ($user->isClean()) {
          
            $arr = array('msg' => 'At least one value must change.', 'status' => false);
        }

     

        $update =User::where('id', $id)
              ->update([
                'branch_id' => $request->branch_id,
                'campaign_id' => $request->campaign_id,
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'pf_no'=>$request->pf_no,
                'phone_number' => $request->phone_number,
                'brand' => $request->brand
            ]);

        if ( $update){
            $arr = array('msg' => 'User update successfull', 'status' => true);
        }else{
        $arr = array('msg' => 'Something went wrong. Please try again!', 'status' => false);
        }
        return Response()->json($arr);


 }


    public function getUsers(Request $request){


        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');

        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; 
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        //Total reacords
        $totalRecords=User::query()->with([
            'role',
            'report',
            'brands',
            'member.contact',
            'campaign',
            'team.team_user.user',
            'schedule',
            'branch',
            'toyota_case',
            'escalate.toyota_case.user',
            'resolution'
        ])
        ->where('name','ILIKE','%'.$searchValue.'%')
        ->orWhere('email','ILIKE','%'.$searchValue.'%')
        ->whereHas('role', function($q) use ($searchValue) {
            $q->where('name','ILIKE','%'.$searchValue.'%');
        })
        ->count();
        $totalRecordswithFilter=$totalRecords;

        //fetch records //
        $records=User::orderBy($columnName,$columnSortOrder)
        ->with([
            'role',
            'report',
            'brands',
            'member.contact',
            'campaign',
            'team.team_user.user',
            'schedule',
            'branch',
            'toyota_case',
            'escalate.toyota_case.user',
            'resolution'
        ])
        ->where('name','ILIKE','%'.$searchValue.'%')
        ->orWhere('email','ILIKE','%'.$searchValue.'%')
        ->whereHas('role', function($q) use ($searchValue) {
            $q->where('name','ILIKE','%'.$searchValue.'%');
        })
        ->skip($start)
        ->take($rowperpage)
        ->get();



        $data_arr = array();
        $t=0;

       

        foreach($records as $record){
            $action=route('edit.user',$record->id);
            $t+=1;           
            $data_arr[] = array(
              "id" =>'&nbsp;&nbsp;'.$t,
              "name" => $record->name,
              "email" =>$record->email,
              "role" => $record->role->name,
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
    }

    /**
     * Add User
     */
    public function new(){

    
        $branches=Branch::all();
        $roles=Role::all();
        $brands=Brand::all();
        $campaigns=Campaign::all();
        return view('users.new',get_defined_vars());
    }

    public function createUser(Request $request){

        $this->validate($request, [
            'branch_id' => ['string', 'max:255', 'exists:branches,id'],
            'campaign_id' => ['string', 'max:255', 'exists:campaigns,id'],
            'role_id' => ['required', 'string', 'max:255', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'numeric'],
            'pf_no'=>['nullable','numeric'],
            'password' => ['required', 'string', 'max:255', 'min:6'],
            'brand' => ['array'] 
        ]);

    
        $user = User::query()->create([
            'branch_id' => $request->branch_id,
            'campaign_id' => $request->campaign_id,
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'brand' => $request->brand,
            'slug'=>Str::slug($request->name),
            'pf_no'=>$request->pf_no,
            'password' => Hash::make($request->password),
        ]);


        // create new brands

        foreach ($request->brand as $brand_id) {
            BrandUser::query()->updateOrCreate([
                'user_id' => $user->id,
                'brand_id' => $brand_id
            ]);
        }


        if ($user){
            $arr = array('msg' => 'User created successfull', 'status' => true);
        }else{
        $arr = array('msg' => 'Something went wrong. Please try again!', 'status' => false);
        }
        return Response()->json($arr);


    }

    /**
     * show user
     */

     public function show2($id){

         /*$user=User::findOrFail($id);

         $user['users']=User::where('level','!=','Analyst')->where('level','!=','Swing Capacity')->get(['id','name']);

         return view('users.edit',['user'=>$user]);*/
     }

     /**
      * Upadate User
      */
      public function updatePassword(Request $request, $id){

        $this->validate($request, [
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        
        ]);

        $userUpdate=User::where('id', $id)
        ->update([
        'password' => Hash::make($request->password),
        ]);


        if ($userUpdate){
            $arr = array('msg' => 'Password reset successfull', 'status' => true);
        }else{
        $arr = array('msg' => 'Something went wrong. Please try again!', 'status' => false);
        }

        return Response()->json($arr);

      }


  

}
