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
            @include('outbound.'.$slug.'.customersidebar')
            </div>
            <div class="col-lg-12 mt-4 justify-content-center align-content-center">
         
            <form action="{{route('question.'.$slug,['q4',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @csrf


                                                        <div class="form-group mb-4">

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
                                   


                                    
                                                            <p>Q3.I am making an after-sale call regarding your experience when you came to purchase a vehicle from us</p>
                                                            <p>On a scale of 0 - 10 (0-Very Unlikely, 10 -Very likely) - How likely is it that you would recommend DT Dobie <b>{{$customer->RetailOutletDealerCode}}</b> to family, friends, colleagues?  </p>
                                                          
           
                                                        
                                                            @for($start=0; $start<=10; $start++)

                                                                <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="q3" id="{{'q3'.$start}}" value="{{$start}}" required>
                                                                <label class="form-check-label" for="{{'q3'.$start}}">{{$start}}</label>
                                                                </div>
                                                            @endfor


                                                            <p><br>Please tell us why you gave this score? </p>

                                                            <textarea class="form-control" name="q3_comments" required id="q3_comments"></textarea>


        
                                                           
                                                            <span class="text-danger">{{ $errors->first('q3') }}</span>
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

<script type="text/javascript">

</script>

@endpush