@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 7')

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
                                   


                                    
                                                            <p>Q7. May I please know what the issue is with the product?  </p>
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" required id="q7" value="Signal Issues – Ticket created" name="q7" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q7">Signal Issues – Ticket created</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="q7b" required value="TV Control Unit Under Performing" name="q7" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q7b">TV Control Unit Under Performing</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q7c" value="Quad Control Unit Underperforming" name="q7" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q7c">Quad Control Unit Underperforming</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio"  required id="q7d" value="Raised Problem – Ticket Created" name="q7" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q7d">Raised Problem – Ticket Created</label>
                                                                
                                                            </div>

                                                          
                                                           

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q7g" value="Others" name="q7" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q7g">Others </label>

                                                                <br>
                                                                <textarea class="form-control" id="q7_others" style="display:none" placeholder="Explain others" name="q7_others"></textarea>
                                                                
                                                            </div>

                                                           
                                                            <span class="text-danger">{{ $errors->first('q7') }}</span>
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

var activationValue =$("input[name='q7']:checked").val(); 

$('#q7_others').val('');

if(activationValue ==='Others'){
    $("#q7_others").css("display", "block");
    
}else{
    $("#q7_others").css("display", "none");
}

});

</script>

@endpush