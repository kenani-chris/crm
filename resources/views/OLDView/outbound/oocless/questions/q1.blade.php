@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 1')

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
         
            <form action="{{route('question.oocless',['q2',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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

                                
                                    
                                                            <p>Q1. 
                                                            <?php
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
                                                                ?>, I am {{Auth::user()->name}} calling you from Azuri solar, am I speaking to {{$customer->CustomerName}}</p>

                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q1" value="Yes" name="q1" class="custom-control-input missingOle required">
                                                            <label class="custom-control-label" for="q1">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="q1b" value="No" name="q1" class="custom-control-input missingOle required">
                                                                <label class="custom-control-label" for="q1b">No</label>
                                                            </div>
                                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q1') }}</span>
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

@push('scripts')

<script text="text/javascript">

function copyNumber() {

    var copyPhone=document.getElementById("phoneNo");
    copyPhone.select();
    copyPhone.setSelectionRange(0, 99999);
    document.execCommand("copy");

}
</script>

@endpush