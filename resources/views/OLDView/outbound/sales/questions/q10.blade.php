@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 10')

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
         
            <form action="{{route('question.'.$slug,['q11',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
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
                                   


                                    
                                                            <p>Q10. Were you introduced to an Aftersales representative? </p>
                                                           
                                                           
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                            <input type="radio" id="q10" required value="Yes" name="q10" class="custom-control-input missingOle">
                                                            <label class="custom-control-label" for="q10">Yes</label>
                                                           
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" required id="q10b" value="No" name="q10" class="custom-control-input missingOle">
                                                                <label class="custom-control-label" for="q10b">No</label>
                                                                
                                                            </div>

                                                            

                                                           
                                                            <span class="text-danger">{{ $errors->first('q10') }}</span>
                                                        </div>

                                                        <div class="form-group mt-4 mb-4">
                                                            <p>Capture VOC</p>
                                                            <textarea class="form-control" required name="q10_comments" id="q10_comments"></textarea>

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