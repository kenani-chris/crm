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
            @include('outbound.oocgreater.customersidebar')
            </div>
            <div class="col-lg-12 mt-4 justify-content-center align-content-center">
         
            <form action="{{route('question.oocless',['q13',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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

                                
                                    
                                                            <p>Q12. If you have any questions/enquiries, please feel free to call us on our customer care number (0709137000) from 07:30 – 21:00. </p>
<p>Thank you for your time and for choosing Azuri Solar. Have a <?php
                                                                $time = date("H");
                                                                $timezone = date("e");
                                                                if ($time < "12") {
                                                                    echo "Good morning";
                                                                } else
                                                                if ($time >= "12" && $time < "17") {
                                                                    echo "Good afternoon";
                                                                } else
                                                                if ($time >= "17" && $time < "19") {
                                                                    echo "Good evening";
                                                                } else
                                                                if ($time >= "19") {
                                                                    echo "Good night";
                                                                }
                                                                ?>. </p>

                                                        </div>

                                                        <div class="col-md-12">
                                                       

                                                        @include('outbound.dispositions.complete')
                                                        </div>

                                                       
                                                   
           
            
                                                    <button class="btn btn-primary btn-rounded ml-4 mt-4" id="surveyContinue" name="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
          
        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection