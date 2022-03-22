@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 9')

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
         
            <form action="{{route('question.'.$slug,['q10',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                                            <p>Q9. Do you feel the time taken to service/repair your vehicle was reasonable? </p>
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q9" required value="Yes" name="q9" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q9">Yes</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q9b" value="No" name="q9" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q9b">No</label>
                                                                
                                                            </div>

                                                            

                                                           
                                                            <span class="text-danger">{{ $errors->first('q9') }}</span>
                                                        </div>

                                                        <div class="form-group mt-4 mb-4">
                                                            <p>Kindly share with us your experience of the services we provided. </p>
                                                            <textarea class="form-control" required name="q9_comments" id="q9_comments"></textarea>

</div>
                                                   
           
            
                                                    <button class="btn btn-primary btn-rounded" id="surveyContinue" name="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                    <button type="button" class="btn btn-outline-danger btn-rounded ml-4 pull-right" data-toggle="modal" data-target="#terminateModal">&nbsp;&nbsp;&nbsp;&nbsp;Terminate Survey <i class="mdi mdi-cancel"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>

        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection

@push('scripts')


@endpush