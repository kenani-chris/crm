@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 6')

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
         
            <form action="{{route('question.oocgreater',['q10',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   


                                    
                                                            <p>Q6. May I please know what happened?  </p>
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" required id="q6" value="Agent did not inform me about the payment plan" name="q6" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q6">Agent did not inform me about the payment plan</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q6b" value="Agent took torch" name="q6" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q6b">Agent took torch</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q6c" value="Agent took the money" name="q6" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q6c">Agent took the money</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q6d" value="Agent took the radio" name="q6" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q6d">Agent took the radio</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q6e" value="Agent promised the system will run 24/7" name="q6" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q6e">Agent promised the system will run 24/7</label>
                                                                
                                                            </div>
                                                           

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q6g" value="Others" name="q6" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q6g">Others</label>

                                                                <br>
                                                                <textarea class="form-control" placeholder="Explain others" style="display:none" name="q6_others" id="q6_others"></textarea>
                                                                
                                                            </div>

                                                           
                                                            <span class="text-danger">{{ $errors->first('q6') }}</span>
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

var activationValue =$("input[name='q6']:checked").val(); 

$('#q6_others').val('');

if(activationValue ==='Others'){
    $("#q6_others").css("display", "block");
    
}else{
    $("#q6_others").css("display", "none");
}

});

</script>

@endpush