@extends('layouts.app')

@section('pageParent', 'Home ')

@section('pageTitle','Today Dashboard Analytics')

@section('content')


<div class="col-md-12">
<h2>{{$greet}} 

</h2>
<h3 style="margin-top:10px; margin-bottom:20px"><span >{{ucwords(Auth::user()->name)}}</span></h3>
</div>




<!--
<div class="col-md-12">

<a href="" class="pull-right"><i class="fa fa-search"></i> Quick Search</a>

</div>



<div class="col-md-12">
<div class="card">
<div class="card-body">
<form method="">
{{ csrf_field() }}

<div class="row">
<div class="col-md-4">
        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="text" id="date_from" class="form-control dateTimePicker" name="date_from">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" id="date_to" class="form-control dateTimePicker" name="date_to">
        </div>
    </div>

    <div class="col-md-3">
<label></label>
    <div class="form-group" style="margin-top:7px">
  
    <button  type="button" class="btn btn-success btn-block pull-right" id="search"><i class="fa fa-search"></i> Search</button>
</div>
    </div>
    <div class="col-md-1">
<label></label>
<div class="form-group" style="margin-top:7px">
   <button class="btn btn-secondary"  type="button" id="refresh"> <i class="mdi mdi-refresh"></i></button>
    </div>
</div>
   

</div>
</form>
</div>
</div>
</div>-->



@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    var date_from=$('input[name="date_from"]').val();
    var date_to=$('input[name="date_to"]').val();
    var _token = $('input[name="_token"]').val();

    dashboard();

    function dashboard(date_from="", date_to=""){
        $.ajax({
            url:"{{route('dashboard')}}",
            method:'POST',
            data:{date_from:date_from,date_to:date_to, _token:_token},
            dataType:"json",
            success:function(data){
                //catalogReceived
                $('#catalogActivated').html(data.catalogActivated);
                $('#catalogReceived').html(data.catalogReceived);
                $('#catalogAssigned').html(data.catalogAssigned);
                $('#catalogCompleted').html(data.catalogCompleted);
                $('#catalogInProgress').html(data.catalogInProgress);
                $('#catalogInReview').html(data.catalogInReview);
                $('#catalogRevision').html(data.catalogRevision);
                $('#stalledRequest').html(data.catalogStalledRequest);
                $('#stalled').html(data.catalogStalled);
                $('#skuAssigned').html(data.sku_assigned);
                $('#skuOwner').html(data.sku_owner);
                $('#completCatalogs').html(data.complete_catalogs);
                $('#TL_Assigned').html(data.catalogTLAssigned);
                $('#catalogPending').html(data.catalogPending)
                
            }
        })
    }

    $('#search').click(function(){
        var date_from=$("#date_from").val();
        var date_to=$("#date_to").val();

        if(date_from!='' && date_to!=''){
            dashboard(date_from,date_to);
        }else{
            dashboard();
        }

    });

    $('#refresh').click(function(){
            $('#date_from').val('');
            $('#date_to').val('');
            dashboard();
    });
});
</script> 
@endpush