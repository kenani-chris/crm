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
         
            <form action="{{route('question.oocless',['q10',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   


                                    
                                                            <p>Q8. May I please know what the missing component is?  </p>
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q8" required value="Missing Radio" name="q8" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q8">Missing Radio</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q8b" value="Missing Torch" name="q8" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q8b">Missing Torch</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q8c" value="Missing Charger" name="q8" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q8c">Missing Charger</label>
                                                                
                                                            </div>


                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q8g" value="Others" name="q8" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q8g">Other </label>

                                                                <br>
                                                                <textarea class="form-control" id="q8_others" style="display:none" placeholder="Explain others" name="q8_others"></textarea>
                                                                
                                                            </div>

                                                           
                                                            <span class="text-danger">{{ $errors->first('q8') }}</span>
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

var activationValue =$("input[name='q8']:checked").val(); 

$('#q8_others').val('');

if(activationValue ==='Others'){
    $("#q8_others").css("display", "block");
    
}else{
    $("#q8_others").css("display", "none");
}

});

</script>

@endpush