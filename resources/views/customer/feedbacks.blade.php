@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'Customer Feedbacks')

@section('content')

<div class="card">
<div class="card-body">

@include('customer.search')

<div class="col-md-12"><br></div>


<div class="table-responsive">

<table id="toyotaCase" class="table table-centered table-nowrap table-striped table-bordered position-relative" style='border-collapse: collapse;'>
    <button id="export-customer-feedback" class="btn btn-primary btn-sm position-absolute export-btns"><span class="mdi mdi-microsoft-excel"></span> Export to Excel </button>
<thead>

<tr>
<th>#</th>
<th>Customer Name</th>
<th>Order Number</th>
<th>Telephone One</th>
<th>Telephone Two</th>
<th>Advisor/Consultant</th>
<th>Dept</th>
<th>Branch</th>
<th>Brand</th>
<th>VOC</th>
<th>Staff Comment</th>
<th>Status</th>
<th>Feedback Type</th>
<th>Comment Summary</th>
<th>Raised Date/Time</th>
<th>Action</th>
<th>Closed Date/Time</th>
</tr>


</thead>
</table>

@include('customer.feedbackmodal')
</div>
</div>
</div>
@endsection


@push('scripts')

<script type="text/javascript">

$(document).ready(function(){
/*########################### Export Logic ##################################*/
$('#export-customer-feedback').css('display','block');
$('#export-customer-feedback').click(function(){
    $('#search-form').attr('action',"{{route('toyota.customer.feedback.export')}}");
    $('#search-form').submit();
});
$('button[type=submit]').click(function(){
    $('#search-form').attr('action',"{{route('resolutions.search')}}");
});
/*###########################################################################*/


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var oTable=$('#toyotaCase').DataTable({
 processing: true,
 serverSide: true,
ajax:{
     url:"{{route('toyota.customer.feedback')}}",
      type: "POST",
      "aaData":'',
        data:function(d){
                d.date_from=$('input[name=date_from]').val();
                d.date_to=$('input[name=date_to]').val();
                d.brand= $('select[name=brand]').val();
                d.branch= $('select[name=branch]').val();
                d.campaign= $('select[name=campaign]').val();
                d.advisor= $('select[name=advisor]').val();
                d.feedback= $('select[name=feedback]').val();
                }
            },
            columns: [
            { data: 'id' },
            {data:'customer'},
            {data: 'order_number'},
            {data: 'telephone_one'},
            {data: 'telephone_two'},
            {data: 'name'},
            {data:'dept'},
            {data:'branch'},
            {data:'brand'},
            {data:'voc_customer'},
            {data:'comments'},
            {data:'is_closed'},
            {data:'feedback'},
            {data:'feedback_summary'},
            {data:'created_at'},
            {data:'action'},
            {data:'closed_at'}
            
 ],
 columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets:9
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets:10
                }
             ],
 responsive: true,
 dom: 'lBrtip', 
 lengthMenu: [[25,50, 100,500,1000,-1], [25,50, 100,500,1000,'All']],
 pageLength: 50,
 /*order: [[ 0, "desc" ]],*/
 buttons: [ ],


});

$('button[type=submit]').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#toyotaCase').on('click', '.closeFeedback', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $("#custid").val(id);


        //Ajax call

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var url = '{{ route("toyota.customer.show", ":id") }}';

        url = url.replace(':id',id);

        $('#voc_customer').html('');
                $('#customer_description').html('Loading...');
                $('#customer').html('Loading...');
                $('#telephone_one').html('Loading...');
                $('#order_number').html('Loading...');
                $('#classification_type').html('Loading...');
                $('#branch').html('Loading...');
                $('#brand').html('Loading...');

        $.ajax({
            method:"GET",
            url:url,
            dataType:"json",
            success:function(data){

                console.log("DATA IS ",data);

                console.log("PHONE NO IS ",data.member.contact.telephone_one);
                $('#voc_customer').html((data.voc_customer));
                $('#customer_description').html((data.member.contact.customer_description));
                $('#customer').html((data.member.contact.customer));
                $('#telephone_one').html(data.member.contact.telephone_one+'/'+data.member.contact.telephone_two);
                $('#order_number').html(data.member.contact.order_number);
                $('#classification_type').html(data.classification_type.name);
                $('#branch').html(data.branch.name);
                $('#brand').html(data.brand.name);

            }
        });

        //ajax call

    });

    //Close

    if($("#closeFeedbackForm").length > 0){
    $("#closeFeedbackForm").validate({

        rules: {
            comments: {
            required: true
            },
            is_closed: {
            required: true
            }
    },
        messages: {
            comments: {
           required: "Please enter your comments",
       }, 
       is_closed: {
            required: "Please select a feedback status"
            }
    },
    submitHandler: function(form) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url="{{route('feedback.update',':id')}}";
        url=url.replace(':id',$('#custid').val())
        $('.closeFButton').html('Closing..');
        $.ajax({
        url:url,
        type: "PUT",
		cache: false,
        data: $('#closeFeedbackForm').serialize(),
        success: function(response) {
            $('.closeFButton').html('Close Feedback');

          
          console.log(response.status)

            if(response.status==true){
                oTable.draw();
                $("#feedbackModal").modal('hide');
                $('#closeFeedbackForm')[0].reset();
            }else{
                //loadModal("none")
            }

        }
        });
        }

    })
}


    //Closed


});





</script>
@endpush