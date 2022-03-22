<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ServiceLeadsImport;
use App\ServiceLeads;
use App\Channel;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $channels=Channel::all();

        return view('leads.index',get_defined_vars());
    }


    public function uploadLeads(Request $request){

        $channel=Channel::findOrFail($request->channel);
        
        $channel_name=str_replace(' ', '', $channel->title);

        $className='App\\Imports\\'.$channel_name.'LeadsImport';
        

        $importClass=new  $className;



        $saveExcel=Excel::import($importClass, $request->file('file')->store('temp'));


        if($saveExcel){
            $arr = array('msg' =>$importClass->getRowCount().' Leads upload successfully',  'status' => true);
        }else{
            $arr = array('msg' => 'Something went wrong. Leads not uploaded!', 'status' => false);
        }

        return Response()->json($arr); 

    }
}
