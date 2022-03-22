@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 4')

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
         
            <form action="{{route('question.oocgreater',['q5',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   


                                    
                                                            <p>Q4. May I please know why you are unable to pay today? </p>
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" required id="q4" value="Financial Constraints" name="q4" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q4">Financial Constraints </label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q4b" value="Agent Related Issues" name="q4" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q4b">Agent Related Issues</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio"  required id="q4c" value="Product Faulty" name="q4" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q4c">Product Faulty</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q4d" value="Missing Components " name="q4" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q4d">Missing Components </label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q4e" value="Using Alternative Light Source" name="q4" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q4e">Using Alternative Light Source </label>
                                                                
                                                            </div>
                                                                       
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q4f" value="Others" name="q4" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q4f">Others</label>

                                                                <br>
                                                                <textarea class="form-control" placeholder="Explain others" style="display:none" name="q4_others" id="q4_others"></textarea>
                                                                
                                                            </div>

                                                           
                                                            <span class="text-danger">{{ $errors->first('q4') }}</span>
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

<script type="text/javascript">
$('.missingOle').click(function() { 

var activationValue =$("input[name='q4']:checked").val(); 

$('#q4_others').val('');

if(activationValue ==='Others'){
    $("#q4_others").css("display", "block");
    
}else{
    $("#q4_others").css("display", "none");
}

});

</script>

@endpush