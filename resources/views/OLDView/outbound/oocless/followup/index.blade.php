@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', "Followups ".$channel)

@section('content')

<div class="col-12">
    <?php
    $channel_id=2;
    ?>
@if(!empty($customer))

@include('outbound.dispositions.followupmodal')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 section-title just">
                                           
            </div>

            <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">Today Payment Followups</a>
        </li>
       <!-- <li class="nav-item">
            <a class="nav-link" id="later-tab" data-toggle="tab" href="#later" role="tab" aria-controls="later" aria-selected="false">Pending Payment Followups</a>
        </li>-->
        
    
        </ul>
        <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
        
        <div class="table-responsive">
                                        
                                        <table id="todayPayment" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                                           
                                           
                                           
                                            <thead>
                                            <tr>
                                                        <th style="width: 20px;">
                                                            # 
                                                        </th>
                                                        <th>Unit Serial No</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone No</th>
                                                        <th>Followups</th>
                                                        <th>Promise Date/Time</th>
                                                        <th>Date Created</th>
                                                        <th>Action</th>
                                                    </tr>
                                            </thead>
                                        </table>

        
        
        </div>

        </div>



        <!--<div class="tab-pane fade" id="later" role="tabpanel" aria-labelledby="later-tab">

        <h2>This is Duncan mwirigi</h2>
        
        
        
        </div>-->
       
        </div>

                    
            
            </div>
        </div>
    </div>
</div>
@else

<div class="alert alert-info">
    No data available for followup
</div>

@endif



 
                            </div> <!-- end col-->
                            @endsection

@push('scripts')
<script type="text/javascript">

$(document).ready(function(){

    var paymentTable=$('#todayPayment').DataTable({
         processing: true,
         serverSide: true,
         ajax:{
             url:"{{route('todayPayments.getlist')}}",
             data:function(d){
                 d.channel="2";
             }
            },  
         columns: [
            { data: 'id' },
            { data: 'UnitSerialNumber'},
            { data: 'CustomerName'},
            { data: 'CustomerPhoneNumber' },
            {data :'no_of_followups'},
            {data:'promise_date_time'},
            {data:'updated_at'},
            {data: "action",
                "render": function(data, type, row, meta){
              if(type === 'display'){
                data =data;
              }

            return data;
         },
        
      }
      
         ],
         responsive: true,
         dom: 'lBrtip', 
         lengthMenu: [[25,50, 100,500,1000,5000,10000, -1], [25,50, 100,500,1000,5000,10000, "All"]],
         pageLength: 25,
         order: [[ 6, "desc" ]],
         buttons: [
                    {
                        extend: 'excel',
                        text: '<span class="mdi mdi-microsoft-excel"></span> Export to Excel',
                        exportOptions: {
                            columns: [1,2,3,4,5,6],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    },
                    
                    {
                        extend: 'csv',
                        text: '<span class="mdi mdi-file-delimited-outline"></span> Export to CSV',
                        exportOptions: {
                            columns: [1,2,3,4,5,6],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    },
                    {
                        extend: 'copy',
                        text: '<span class="mdi mdi-content-copy"></span> Copy',
                        exportOptions: {
                            columns: [1,2,3,4,5,6],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<span class="fa fa-print"></span> Print',
                        exportOptions: {
                            columns: [1,2,3,4,5,6],
                            modifier: {
                                search: 'applied',
                                order: 'applied',
                            }
                        }
                    }
                ],
      });


      if($("#paymentFollowupForm").length > 0){
    $("#paymentFollowupForm").validate({

        rules: {
            q3: {
            required: true
        }
    },
        messages: {
            q3: {
           required: "Choose followup status",
       }, 
    },
    submitHandler: function(form) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        loadModal("block");
        $('.followPayment').html('Confirming Followup....');
        $.ajax({
        url: "{{route('followup.store', $channel_id)}}",
        type: "POST",
        data: $('#paymentFollowupForm').serialize(),
        success: function(response) {
            $('.followPayment').html('Confirm Followup');


            if(response.msg){
                //window.location=response.url;

                
                sweetAleart(response.msg);
                loadModal("none");
                $('#followupModal').modal('hide');
                paymentTable.draw();

                /*window.setTimeout(function () {
                window.location.reload();
                }, 3000);*/

            }else{
                loadModal("none")
            }

        }
        });
        }

    })
}

function sweetAleart(msg){

swal({
    title: "Success",
    text: msg,
    button: "Close", // Text on button
    icon: "success", //built in icons: success, warning, error, info
    timer: 3000, //timeOut for auto-close
     buttons: {
       confirm: {
         text: "Close",
         value: true,
         visible: true,
         className: "",
         closeModal: true
       },
       cancel: {
         text: "Cancel",
         value: false,
         visible: true,
         className: "",
         closeModal: true,
       }
     }
    });
}


});
var channel=1;
</script>
@endpush