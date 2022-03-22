<div class="col-lg-12 justify-content-center align-content-center">
<div class="col-lg-12 mt-2">
                @include('outbound.'.$slug.'.customersidebar')
                </div>
<form action="{{route('survey.intro.'.$slug,$customer->id)}}" id="newLeadUpload" method="POST" enctype="multipart/form-data">
@csrf
<div class="form-group mt-2">
  Customer Phone No

    <h2><span style="font-size:40px">
    
    <input type="text" value="{{$customer->MobileNumber}}"  name="phoneNo" id="phoneNo" style="border:none; width:300px">
    
    <sup><a href="javacsript::void()" onclick="copyNumber()" ><i class="fa fa-copy" title="Copy to clip board"></i></a></sup></span> 
    
    
    </h2> 
    <hr>
</div>


                                            <div class="form-group mt-2 mb-2">

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


                                                @foreach($customer->disposition_types as $diposition_type)

                                                <div class="custom-control custom-radio custom-control-inline ">
                                                <input type="radio" id="{{$diposition_type->slug.'_'.$call}}" required value="{{$diposition_type->id}}" name="callStatus" class="custom-control-input missingOle">
                                                <label class="custom-control-label" for="{{$diposition_type->slug.'_'.$call}}">{{$diposition_type->title}}</label>
                                                </div>


                                                @endforeach

                                               
                                                                
                                               
                                                <span class="text-danger">{{ $errors->first('callStatus') }}</span>
                                            </div>
                                       


<button class="btn btn-primary btn-rounded" id="surveyContinue">&nbsp;&nbsp;&nbsp;&nbsp;Continue <i class="mdi mdi-arrow-right"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>

</form>

</div>