@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 5')

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
                                   


                                    
                                                            <p>Q5. I am sorry, may I please know what exactly is the challenge?  </p>
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q5" required value="Loss of Income source" name="q5" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q5">Loss of Income source</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5b" value="Loss of Family Member" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5b">Loss of Family Member</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5c" value="Need to prioritize other expenses" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5c">Need to prioritize other expenses</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5d" value="Had a sick family member" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5d">Had a sick family member</label>
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5e" value="Cannot work–sick" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5e">Cannot work–sick</label>
                                                                
                                                            </div>
                                                                       
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5f" value="Lost breadwinner" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5f">Lost breadwinner </label>

                                                            
                                                                
                                                            </div>

                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q5g" value="Others" name="q5" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q5g">Others</label>

                                                                <br>
                                                                <textarea class="form-control" style="display:none" name="q5_others" placeholder="Explain others" id="q5_others"></textarea>
                                                                
                                                            </div>

                                                           
                                                            <span class="text-danger">{{ $errors->first('q5') }}</span>
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

var activationValue =$("input[name='q5']:checked").val(); 

$('#q5_others').val('');

if(activationValue ==='Others'){
    $("#q5_others").css("display", "block");
    
}else{
    $("#q5_others").css("display", "none");
}

});

</script>

@endpush