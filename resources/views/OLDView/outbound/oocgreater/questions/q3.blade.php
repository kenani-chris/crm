@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 3')

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
         
            <form action="{{route('question.oocgreater',['q4',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   


                                    
                                                            <p>Q3. 
                                                            I am calling to inform you that you have since paid <b>{{number_format($customer->AmountPaid)}}</b> and your outstanding balance is <b>{{number_format($customer->PaymentPlanOutstandingBalance)}}</b> for you to enjoy <b>{{$customer->Product}}</b> for free. 
                                                            <p>You have a total of <b>Ksh {{number_format($customer->EWallet)}}</b> on your e-wallet account. Kindly top up <b>Ksh {{number_format(($customer->PaymentPlanOutstandingBalance)-($customer->EWallet))}}</b> for you to enjoy our services. </p><p>What time should I expect you to make the payments? </p>

                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" required id="q3" value="Today" name="q3" class="custom-control-input q3Activate">
                                                            <label class="custom-control-label" for="q3">Today</label>
                                                            <div class="form-group q3_time" style="display:none"><label class="control-label">Time </label>
                                                           
                                                            <input type="time" name="q3_time" id="q3_time" style="padding:2px; margin-top:5px"></input>
                                                        </div>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q3b" value="Later" name="q3" class="custom-control-input q3Activate">
                                                                <label class="custom-control-label" for="q3b">Later</label>
                                                              
                                                                <div class="form-group q3_date" style="display:none"><label class="control-label">Time </label>
                                                                        <input type="date" name="q3_date" id="q3_date" style="padding:2px; margin-top:5px"></input>
                                                                </div>
                                                            </div>
                                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q3') }}</span>
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

</script>

@endpush