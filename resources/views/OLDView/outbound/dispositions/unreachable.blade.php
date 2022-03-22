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
            @elseif($slug=="parts")
            <form action="{{route('question.'.$slug,['q12',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @elseif($slug=="sales")
            <form action="{{route('question.'.$slug,['q13',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @elseif($slug=="bodyshop")
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

                                                            @foreach($customer->unreachable_dispositions as $disposition)
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                              <input type="radio" required id="{{$disposition->slug}}" value="{{$disposition->id}}" name="disposition" class="custom-control-input missingOle">
                                                              <label class="custom-control-label" for="{{$disposition->slug}}">{{$disposition->title}}</label>
                                                              </div>
                                                          @endforeach
                                                                      
                                                                      
                                                                            
                                                           
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