<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Customer;
use Illuminate\Http\Request;
use Auth;

class CX3Controller extends Controller
{

       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api')->except('logout');
    }


     public function contactLookup(Request $request){

      $number=str_replace(' ','',$request->query('number'));

     
      $data=Customer::where('phone_no',$number)->first(['id','name as firstname','branch_id as company','email','phone_no as mobilephone','url']);

        $message=$data;
      return response()->json(['contact' => $message], 200);


     }

     /**
      * Contact creation
      */

      public function contactCreation(Request $request){

        $data=$this->createRecord($request->mobilephone, $request->firstname);
    
        return response()->json(['data' => $data], 200);
      }

      /**
       * Call journaling
       */
      public function callJournaling(Request $request){

        $data="call journalling";
       return response()->json(['data' => $data], 200);


      }

      /**
       * Show
       */
      public function contactIDShow(Request $request){

        $contactID=$request->query('ContactID');

        $data="Contact ID ".$contactID;
        return response()->json(['data' => $data], 200);


      }

      /**
       * Create Call
       */

       public function createRecord($phone_no,$name){
        $customer=new Customer();

        $customer->phone_no = $phone_no;
        $customer->name = $name;
        //$customer->user_id=Auth::user()->id
        //$customer->save();
       }
}
