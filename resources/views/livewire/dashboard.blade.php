@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'Dashboard')

@section('content')

<div class="col-md-12 row"><a href="#" class="toggleDashboard"><i class="fa fa-search pull-right mb-2 text-danger" title="Filter Dashboard"></i></a></div>

@include('livewire.search')



<div class="col-md-12 row">



                <div class="card col-md-4">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            Total Dialed
                           
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-b-0 font-weight-800 text-pink" id="dialed"><div class="ole_loader"></div></h2>
                            <div class="icon-block icon-block-xl icon-block-floating icon-block-outline-danger">
                                <i class="mdi mdi-layers fa-2x text-pink"></i>
                            </div>
                        </div>
                        <small class="text-black-50 todays">Last 30 days</small>
                    </div>
                </div>

                <div class="card col-md-4">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            Reachable Contacts
                           
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-b-0 font-weight-800 text-warning" id="reachable"><div class="ole_loader"></div></h2>
                            <div class="icon-block icon-block-xl icon-block-floating icon-block-outline-success">
                                <i class="mdi  mdi-phone-in-talk fa-2x text-warning"></i>
                            </div>
                        </div>
                        <small class="text-black-50 todays">Last 30 days</small>
                    </div>
                </div>

                <div class="card col-md-4">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            Total Completed Surveys
                          
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-b-0 font-weight-800 text-blue" id="complete"><div class="ole_loader"></div></h2>
                            <div class="icon-block icon-block-xl icon-block-floating icon-block-outline-white opacity-5">
                                <i class="mdi mdi-chart-bar fa-2x text-blue"></i>
                            </div>
                        </div>
                        <small class="opacity-5 todays">Last 30 days</small>
                    </div>
                </div>

                <div class="card col-md-4">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            Callbacks
                            
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-b-0 font-weight-800 text-info" id="callbacks"><div class="ole_loader"></div></h2>
                            <div class="icon-block icon-block-xl icon-block-floating icon-block-outline-white opacity-5">
                                <i class="mdi mdi-phone-missed fa-2x text-info"></i>
                            </div>
                        </div>
                        <small class="opacity-5 todays">Last 30 days</small>

                    </div>
                </div>

                <div class="card col-md-4">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            Unreachable Contacts
                           
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-b-0 font-weight-800 text-danger" id="unreachable"><div class="ole_loader"></div></h2>
                            <div class="icon-block icon-block-xl icon-block-floating icon-block-outline-success">
                                <i class="mdi mdi-cellphone-link-off fa-2x text-danger"></i>
                            </div>
                        </div>
                        <small class="text-black-50 todays">Last 30 days</small>
                    </div>
                </div>

                

            </div>


@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){


    var from_date=$('input[name="from_date"]').val();
    var to_date=$('input[name="to_date"]').val();
    var campaign_id=$('select[name=campaign_id]').val();
    var branch_id=$('select[name=branch_id]').val();
    var brand_id=$('select[name=brand_id]').val();

    dashboard(from_date,to_date,campaign_id, branch_id,brand_id);

    function dashboard(from_date="",to_date="",campaign_id="", branch_id="",brand_id=""){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            method:"POST",
            url:"{{route('home.dashboard')}}",
            data:{
                from_date,
                to_date,
                campaign_id,
                branch_id,
                brand_id
            },
            dataType:"json",
            success:function(data){

                console.log("data is ",data);
                $('#callbacks').html(numberWithCommas(data.callbacks));
                $('#complete').html(numberWithCommas(data.complete));
                $('#dialed').html(numberWithCommas(data.dialed));
                $('#leads').html(numberWithCommas(data.leads));
                $('#reachable').html(numberWithCommas(data.reachable));
                $('#unreachable').html(numberWithCommas(data.unreachable));
                $('.todays').html(data.todays);

            }
        });


    }

    $('#search').click(function(e){    
        e.preventDefault();       
        var from_date=$('input[name="from_date"]').val();
        var to_date=$('input[name="to_date"]').val();
        var campaign_id=$('select[name=campaign_id]').val();
        var branch_id=$('select[name=branch_id]').val();
        var brand_id=$('select[name=brand_id]').val();

      
            dashboard(from_date,to_date,campaign_id, branch_id,brand_id);
    });

    $('#refresh').click(function(e){
            var from_date=$('input[name="from_date"]').val();
            var to_date=$('input[name="to_date"]').val();
            var campaign_id=$('select[name=campaign_id]').val();
            var branch_id=$('select[name=branch_id]').val();
            var brand_id=$('select[name=brand_id]').val();
            dashboard(from_date,to_date,campaign_id, branch_id,brand_id);
    });




});

</script>

@endpush