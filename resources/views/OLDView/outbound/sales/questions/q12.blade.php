@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 12')

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
         
            <form action="{{route('question.'.$slug,['q13',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   
                                                            <p>Thank you for choosing DT Dobie, We appreciate your time. Have a <?php
                                                                $time = date("H");
                                                                $timezone = date("e");
                                                                if ($time < "12") {
                                                                    echo "good day";
                                                                } else
                                                                if ($time >= "12" && $time < "17") {
                                                                    echo "good afternoon";
                                                                } else
                                                                if ($time >= "17" && $time < "19") {
                                                                    echo "good evening";
                                                                } else
                                                                if ($time >= "19") {
                                                                    echo "good night";
                                                                }
                                                                ?>. </p>

                                            

                                                            @foreach($customer->complete_call as $complete_call)

                                                            @if($complete_call->slug=="complete-call")
                                                         
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input checked type="radio" id="{{$complete_call->id}}" required value="{{$complete_call->id}}" name="disposition" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="{{$complete_call->id}}">{{$complete_call->title}}</label>
                                                           
                                                            </div>
                                                            @endif

                                                            @endforeach

                                                            <span class="text-danger">{{ $errors->first('disposition') }}</span>
 
                                                        </div>
                                                        
                                                    <button class="btn btn-primary btn-rounded" id="surveyContinue" name="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                    <button type="button" class="btn btn-outline-danger btn-rounded ml-4 pull-right" data-toggle="modal" data-target="#terminateModal">&nbsp;&nbsp;&nbsp;&nbsp;Terminate Survey <i class="mdi mdi-cancel"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>

        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection