<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Channel;
use App\CommentSummary;

use Redirect;
use App\Disposition;
use App\DispositionTypes;
use Auth;
use DB;

class SurveyController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function channel(){

    $channels=Channel::all();

    return view('outbound.joinchannel',get_defined_vars());
  }


  public function predictiveDial(Request $request, $channel){


      /*if($channel==1){

        $this->validate($request, [
          'search_phone_no' => 'required',
        ]);

        $toModel=new OOCGreater();
        $toview="outbound.oocgreater.index";

        $channel="OOC Greater than 50% Channel";

      }else if($channel==2){

        $this->validate($request, [
          'search_phone_no' => 'required',
        ]);

        $toModel=new OOCLess();
        $toview="outbound.oocless.index";

        $channel="OOC Less than 50% Channel";


      }else{

        $toModel="";
      }


      if(!empty($toModel)){

        $created_at=date('Y-m-d');

      

        $customer=$toModel::where('CustomerPhoneNumber','like','%'.$request->search_phone_no.'%')->where('lastDisposition','Pending')->whereDate('created_at',$created_at)->orderBy('created_at','desc')->first();
        
        if( !empty($customer)){
            return view($toview,['channel'=> $channel,'customer'=>$customer, 'error'=>false]);
        }else{
          $msg='Records for Phone No '.$request->search_phone_no.' not found';
          return view($toview,['channel'=> $channel, 'error'=>true, 'msg'=>$msg]);

        }
      }else{
        return view($toview,['channel'=> $channel,'error'=>true,'msg'=>$msg]);
      }

     */

  }


  public function channelIntro(Request $request, $channel){


    if($request->isMethod('post')){

    $this->validate($request, [
      'channel' => 'required|exists:channels,id',
    ]);

      $reqchannel=ucwords($request->channel);
    
  }else{
      $reqchannel=ucwords($channel);
  }


    $created_at=date('Y-m-d');


    $getChannel=Channel::findOrFail($reqchannel);


    $channel=$getChannel->title.' Channel';

    $channel_name=str_replace(' ', '',$getChannel->title);

    $modelName='App\\'.$channel_name.'Leads';
    
    $leadClass=new  $modelName;

    $slug=strtolower($channel_name);

    $customer= $leadClass::inRandomOrder()->where('lastDisposition','Pending')
    ->first();

    $get_view="outbound.".$slug.'.index';



    $callBack_id=Disposition::firstWhere('slug','call-back')->id;

    $unreachable_id=DispositionTypes::firstWhere('slug','not-reachable')->id;

    $sortDirection = 'desc';

    $customer_callback=$leadClass::inRandomOrder()->where('lastDisposition', $callBack_id)
    ->with(['survey'])
    ->whereHas('survey',function ($query) use($sortDirection,$callBack_id) {
        $query->where('user_id', Auth::user()->id)->where('disposition_id',$callBack_id)->where('callback','<=', date('Y-m-d H:i:s'))->orderBy('created_at', $sortDirection);
    })
    ->first();

    
    $call_unreachables=$leadClass::inRandomOrder()
    ->where('updated_at','<=', Carbon::now()->subHours(2)->toDateTimeString())
    ->with(['disposition'])
    ->whereHas('disposition', function($query) use ($unreachable_id){
      $query->where('disposition_type_id', $unreachable_id);
    })
    ->where('attempts','<',12)
    ->with(['survey'])
    ->whereHas('survey',function ($query) use($sortDirection) {
        $query->where('user_id', Auth::user()->id)->orderBy('created_at', $sortDirection);
    })
    ->first();



    return view($get_view, ['channel'=> $channel,'customer'=>$customer, 'slug'=>$slug, 'customer_callback'=>$customer_callback, 'call_unreachables'=>$call_unreachables]);
  }

  /**
   * Save Disposation
   */

  public function getCommentSummary(Request $request){

    try{

      $commentSummary=CommentSummary::where('comment_type_id', $request->comment_type)->where('channel_id', $request->department)->get();

      if($commentSummary->count()>0){

        $arr=array('msg' =>$commentSummary, 'status' => true);

      }else{
        $arr=array('msg' =>"No data found", 'status' => false);
      }

    }catch(Throwable $e){
      $arr=array('msg' =>$e, 'status' => false);
    }

    return Response()->json($arr);
    
  }


}
