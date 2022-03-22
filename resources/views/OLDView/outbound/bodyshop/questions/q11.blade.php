@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel. 'Question 11')

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
         
            <form action="{{route('question.'.$slug,['q12',$customer->id])}}" id="survey1" method="post" enctype="multipart/form-data">
            @csrf


                                                        <div class="form-group">

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
                                   


                                    
                                                            <p>Comment Type</p>


                                                            @foreach($customer->comment_types as $comment_type)

                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input oleSummary" type="radio" name="q11_comment_type_id" id="{{$comment_type->id}}" value="{{$comment_type->id}}" required>
                                                                <label class="form-check-label" for="{{$comment_type->id}}">{{$comment_type->title}}</label>
                                                                </div>

                                                            @endforeach
                                                           
                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q11_comment_type_id') }}</span>
                                                        </div>

                                                        <div class="form-group">

                                                        <p>Department</p>


                                                            @foreach($customer->survey_channels as $channel)

                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input oleSummary" type="radio" name="q11_channel_id" id="{{$comment_type->id}}" value="{{$channel->id}}" required>
                                                                <label class="form-check-label" for="{{$channel->id}}">{{$channel->title}}</label>
                                                                </div>

                                                            @endforeach
                                                           
                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q11_channel_id') }}</span>

</div>

                                                    <div class="form-group">

                                                    <p>Comment Summary</p>

                                                    <select class="form-control" required  name="q11_comment_summary_id" id="comment_summary">
                                                            <option value="">Choose summary</option>
                                                        </select>
                                                        
                                                    
                                                        <span class="text-danger">{{ $errors->first('q11_comment_summary_id') }}</span>

                                                    </div>
                                                   
                                                    <div class="form-group">

                                                        <p>Action required</p>

                                                        <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="q11_action_required" id="q11_action_required_a" value="Yes" required>
                                                                <label class="form-check-label" for="q11_action_required_a">Yes</label>
                                                                </div>

                                                                <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="q11_action_required" id="q11_action_required_b" value="No" required>
                                                                <label class="form-check-label" for="q11_action_required_b">No</label>
                                                                </div>

                                               
                                                           
                                                            
                                                           
                                                            <span class="text-danger">{{ $errors->first('q11_action_required') }}</span>

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
$('.oleSummary').click(function() {

var my_url="{{route('comment.summary')}}";
var comment_type=$("input[name='q11_comment_type_id']:checked").val(); 
var department=$("input[name='q11_channel_id']:checked").val();

if(comment_type && department){

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    url:my_url,
    type:'post',
    dataType:'json',
    data: {
        comment_type:comment_type,
        department:department
    },
    success:function(response){
        var len = response.msg.length;
        $("#comment_summary").empty();
        $("#comment_summary").append("<option value=''>Choose summary</option>");
        for( var i = 0; i<len; i++){
                var id = response.msg[i]['id'];

                var name = response.msg[i]['comment_summary'];
                
                $("#comment_summary").append("<option value='"+id+"'>"+name+"</option>");

            }


    }

});
 
}else{

}



});
</script>

@endpush