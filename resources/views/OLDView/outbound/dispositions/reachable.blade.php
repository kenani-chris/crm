@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Unreachable')

@section('content')
<div class="col-12">


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 section-title just">
                                           
            </div>
            <div class="col-lg-12">
            @include('outbound.'.$slug.'.customersidebar')
            </div>
            <div class="col-lg-12 mt-4 justify-content-center align-content-center">

         
            @if($slug=="service")
            <form action="{{route('question.'.$slug,['q12',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @elseif($lug=="parts")
            <form action="{{route('question.'.$slug,['q12',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @elseif($lug=="sales")
            <form action="{{route('question.'.$slug,['q12',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @elseif($lug=="bodyshop")
            <form action="{{route('question.'.$slug,['q13',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @else
            <script>window.location = "/join/channel";</script>
            @endif
            @csrf


                                                        <div class="form-group mt-4 mb-4">

                                             <div id="msg_div">
                                                    <span class="alert alert-success d-none"  id="res_message"></span>
                                                    </div>
                                                    
                                
                                                                        <div class="alert alert-danger" style="display:none">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                
                                    
                                                            <p>
                                                            Select a disposition</p>

                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="disposition" required value="Commit To Pay" name="disposition" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="disposition">Commit To Pay</label>
                                                            </div>
                                                           
                                                           

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="dispositiong" value="Call Back" name="disposition" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="dispositiong">Call Back</label>
                                                                <div class="form-group dispo_call mt-2" style="display:none">
                                                                <label class="control-label">What is the right time and date to call back? </label>
                                                                        <input type="datetime-local" name="dispo_call" id="dispo_call" style="padding:2px; margin-top:5px"></input>
                                                                </div>
                                                            </div>
                                                                      
                                                                      
                                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('disposition') }}</span>
                                                        </div>
                                                   
           
            
                                                    <button class="btn btn-primary btn-rounded ml-4" id="surveyContinue" name="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
          
        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection