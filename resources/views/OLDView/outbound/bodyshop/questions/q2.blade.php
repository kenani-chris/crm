@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 2')

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
         
            <form action="{{route('question.'.$slug,['q3',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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

                                
                                    
                                                            <p>Q2. 
                                                            Is this time convenient to speak to you? </p>

                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q2" value="Yes" name="q2" required class="custom-control-input missingOle q2Activate">
                                                            <label class="custom-control-label" for="q2">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="q2b" value="No" required name="q2" class="custom-control-input missingOle q2Activate">
                                                                <label class="custom-control-label" for="q2b">No</label>

                                                                <div class="callback-div" style="display:none">
                                                                <label>What is the convenient time to call you back? <em>(Agent Instructions : Set a Call back date and time)</em></label>
                                                                <input type="datetime-local" id="callback_time" name="callback_time" class="callback_time" style="display:none">
                                                                </div>
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="q2c" value="Not Interested" required name="q2" class="custom-control-input missingOle q2Activate">
                                                                <label class="custom-control-label" for="q2c">Not Interested</label>
                                                            </div>
                                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q2') }}</span>
                                                        </div>
                                                   
           
            
                                                    <button class="btn btn-primary btn-rounded ml-4" id="surveyContinue" name="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                    <button type="button" class="btn btn-outline-danger btn-rounded ml-4 pull-right" data-toggle="modal" data-target="#terminateModal">&nbsp;&nbsp;&nbsp;&nbsp;Terminate Survey <i class="mdi mdi-cancel"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>

        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection