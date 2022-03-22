<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Brand;
use App\Models\Answer;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\ToyotaCase;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\ClassificationType;
use Illuminate\Support\Facades\Auth;

class ToyotaCaseController extends Controller
{
    
    public function __construct(){

        $this->middleware('auth');
    }


    public function index(){

        $campaigns=Campaign::all();
        $branches=Branch::all();
        $brands=Brand::all();
        $feedbacks=ClassificationType::all();
        $advisors=User::with(['role'])->whereHas('role', function($q){
            $q->where('slug','champion')->orWhere('slug','advisor');
        })->get();

        return view('customer.feedbacks',get_defined_vars());
    }

    public function update(Request $request, string $id){

        $toyotaCase = ToyotaCase::findOrFail($id);
        
        if(date('Y-m', strtotime($toyotaCase->created_at)) == date('Y-m', strtotime(now()))){

            $this->validate($request, [
                'comments' => 'required|string',
                'is_closed'=>'integer|required|in:1',
            ]);


            $toyotaCase->fill([
                'comments' => $request->comments,
                'is_closed' => $request->is_closed,
                'closed_at' => now(),
                'is_disabled' => true,
            ]);

            if ($toyotaCase->isClean()) {
                $arr = array('msg' =>'At least one value must change.', 'status' => false);
            }else{
                if($toyotaCase->save()){
                    $arr = array('msg' =>$toyotaCase, 'status' => true);
                }else{
                    $arr = array('msg' =>'Feedback was not closed. Please try again', 'status' => false);
                }
            }
        }else{
            $arr = array('msg' =>'Feedback time has been expired hence it was not closed.', 'status' => false);
        }

        return Response()->json($arr);

    }

    public function customerFeedback(Request $request){

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

        if(!empty($request->advisor)){
            $user_pf_no = User::findOrFail($request->advisor);
        }else{
            $user_pf_no = null;
        }


        
        $totalRecords=ToyotaCase::with([
            'user',
            'campaign',
            'branch',
            'brand',
            'member.contact',
            'voc_category',
            'classification_type',
            'classification'
        ])
        ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
            $q->whereDate('created_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('created_at', '<=',date('Y-m-d', strtotime($request->date_to)));
        })
       ->when((Auth::user()->role->slug=='advisor') || (Auth::user()->role->slug=='champion'), function($q){
           $q->whereHas( 'member.contact', function($q) {
            $q->where('created_by', Auth::user()->pf_no);
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
    ->when((!empty($request->brand)), function($q) use($request){
        $q->with('brand')->whereHas('brand', function($query) use($request){
            $query->where('id','ILIKE','%'.$request->brand.'%');
        });
    })
    ->when((!empty($request->feedback)), function($q) use($request){
        $q->with('classification_type')->whereHas('classification_type', function($query) use($request){
            $query->where('id','ILIKE','%'.$request->feedback.'%');
        });
    })
    ->when((!empty($request->advisor)), function($q) use($request, $user_pf_no){
        $q->with('member.contact')->whereHas('member.contact', function($query) use($request, $user_pf_no){
            $query->where('created_by',$user_pf_no->pf_no);
        });
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
                                'classification'
                            ])
                            ->when((!empty($request->date_from) && !empty($request->date_to)), function($q) use($request){
                                $q->whereDate('created_at', '>=',date('Y-m-d', strtotime($request->date_from)))->whereDate('created_at', '<=',date('Y-m-d', strtotime($request->date_to)));
                            })
                            ->when(Auth::user()->role->slug=='advisor' || Auth::user()->role->slug=='champion', function($q){
                                $q->whereHas( 'member.contact', function($q) {
                                 $q->where('created_by', Auth::user()->pf_no);
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
                            ->when((!empty($request->brand)), function($q) use($request){
                                $q->with('brand')->whereHas('brand', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->brand.'%');
                                });
                            })
                            ->when((!empty($request->feedback)), function($q) use($request){
                                $q->with('classification_type')->whereHas('classification_type', function($query) use($request){
                                    $query->where('id','ILIKE','%'.$request->feedback.'%');
                                });
                            })
                            ->when((!empty($request->advisor)), function($q) use($request, $user_pf_no){
                                $q->with('member.contact')->whereHas('member.contact', function($query) use($request, $user_pf_no){
                                    $query->where('created_by', $user_pf_no->pf_no);
                                });
                            })
                            ->skip($start)
                            ->take($rowperpage)
                            ->get();
            $data_arr = array();


           
            $t=0;
            foreach($records as $record){
                $t+=1;

                $enquiries = ClassificationType::findOrFail($record->classification_type_id);

                // date('Y-m', strtotime(now()))

                if(empty($record->comments) && ($enquiries->slug == "enquiries" || $enquiries->slug == "negative")){
                    if(date('Y-m', strtotime($record->created_at)) == date('Y-m', strtotime(now()))){
                        $action = '<a href="#closeFeedback" class="btn btn-danger btn-sm closeFeedback listOle" data-toggle="modal" id="closeFeedback" data-target="#feedbackModal" data-id='.$record->id.' data-attr="'.$record->id.'"><i class="mdi mdi-close"></i>Close</a>
                        ';
                    }else{
                        $action = '<a class="btn btn-danger btn-sm closeFeedback listOle disabled" disabled><i class="mdi mdi-close"></i>Close</a>
                        ';
                    }
                }else{
                    $action = "<span class='text-succcess'><i class='fa fa-check-square fa-lg text-center text-success'></i> Closed </span>";
                }

                if(empty($record->comments) && ($enquiries->slug == "enquiries" || $enquiries->slug == "negative")){
                    
                    if(date('Y-m', strtotime($record->created_at)) == date('Y-m', strtotime(now()))){
                        $customer_ole = '<a href="#closeFeedback" class="text-danger closeFeedback listOle" data-toggle="modal" id="closeFeedback" data-target="#feedbackModal" data-id='.$record->id.' data-attr="'.$record->id.'">'.$record->member->contact->customer.'<sup><i class="mdi mdi-close"></i></sup></a>
                        ';
                    }else{
                        $customer_ole = '<a class="text-danger closeFeedback listOle disabled">'.$record->member->contact->customer.'<sup><i class="mdi mdi-close"></i></sup></a>
                        ';
                    }
                }else{
                    $customer_ole = "<span class='text-succcess'>".$record->member->contact->customer." <sup><i class='mdi mdi-check'></i></sup></span>";
                }

                if(!empty($record->member) && !empty($record->member->contact)&& !empty($record->member->contact->created_by)){
                    $advisor = User::select('name')->where('pf_no',$record->member->contact->created_by)->first();
                }else{
                    $advisor = 'N/A';
                }
                
                // 'name'=>isset($record->escalate->user->name) ? $record->escalate->user->name : '',

                $data_arr[] =array(
                    "id" =>'&nbsp;&nbsp;'.$t,
                    'customer' =>$customer_ole,
                    'order_number'=>$record->member->contact->order_number,
                    'telephone_one'=>$record->member->contact->telephone_one,
                    'telephone_two'=>$record->member->contact->telephone_two,
                    'name'=>isset($advisor->name) ? $advisor->name : 'N/A',
                    'dept'=>$record->campaign->name,
                    'branch'=>$record->branch->name,
                    'brand'=>$record->brand->name,
                    'voc_customer'=>$record->voc_customer,
                    'comments'=>$record->comments,
                    'is_closed'=> (empty($record->comments) && ($enquiries->slug == "enquiries" || $enquiries->slug == "negative")) ? '<span class="text-danger">Open</span>' :'<span class="text-success">Closed</span>',
                    'feedback'=>$record->classification_type->name,
                    'feedback_summary'=>$record->classification->name,
                    'created_at'=>date('Y-m-d H:i:s', strtotime($record->created_at)),
                    'action'=>$action,
                    'closed_at' => !empty($record->closed_at) ? $record->closed_at : 'N/A'


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
     * Export to excel
     */

    /**
     * Get Customer Data
     */

     public function show(string $id){

        return response()->json(ToyotaCase::with([
            'user',
            'escalate.user',
            'campaign',
            'branch',
            'brand',
            'member.contact',
            'voc_category',
            'classification_type',
            'classification'
        ])->findOrFail($id));
     }
}
