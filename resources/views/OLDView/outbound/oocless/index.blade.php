@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel)

@section('content')
<div class="col-12">


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 section-title just">
                                           
            </div>




@if(!empty($customer))



            <div class="col-lg-12">
            @include('outbound.oocgreater.customersidebar')
            </div>
            <div class="col-lg-12 mt-4 justify-content-center align-content-center">
         
            <form action="{{route('survey.intro.less',$customer->id)}}" id="newLeadUpload" method="POST" enctype="multipart/form-data">
            @csrf
<hr>
            <div class="form-group">
              Customer Phone No

                <h2><span style="font-size:40px">
                
                <input type="text" value="{{$customer->CustomerPhoneNumber}}"  name="phoneNo" id="phoneNo" style="border:none; width:300px">
                
                <sup><a href="javacsript::void()" onclick="copyNumber()" ><i class="fa fa-copy" title="Copy to clip board"></i></a></sup></span> <span style="font-size:15px; margin-left:10%">Alt No {{$customer->CustomerPhoneNumberAlternative}}</span></h2> 
            </div>
            <hr>

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

                                
                                    
                                                            <label>Call outcome</label>

                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="callStatus" required value="Reachable" name="callStatus" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="callStatus">Reachable</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="callStatus1" value="Unreachable" name="callStatus" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="callStatus1">Unreachable</label>
                                                            </div>
                                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('callStatus') }}</span>
                                                        </div>
                                                   
           
            
            <button class="btn btn-primary btn-rounded ml-4" id="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
          
        </form>

            </div>
           
            @else
<div class="col-md-12">
            <div class="alert alert-info">
                No leads available to call
            </div>
            </div>

@endif

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