
<div class="table-responsive">
				

<table id="monthlyReports" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
    <button id="sales-report-monthly" class="btn btn-primary btn-sm position-absolute export-btns"><span class="mdi mdi-microsoft-excel"></span> Export to Excel </button>

<thead>

<tr>
<th>#</th>
<th>Date Of Delivery</th>
<th>Customer</th>
<th>Customer Name</th>
<th>Customer Tel.</th>
<th>Customer Classification</th>
<th>Reg. No</th>
<th>VIN</th>
<th>Model</th>
<th>New/Used</th>
<th>Service Advisor</th>
<th>Branch</th>
<th>Brand</th>
<th>DBM Order Code</th>
<th>Job Card Number	</th>
<th>Completed Work Summary	</th>
<th>Gatepass Status	</th>
<th>Survey Type</th>
<th>Q. 4 I am making an after sale call regarding your experience when you came to purchase a vehicle from us. On a scale of 0-10 (0=Very Unlikely, 10=Very Likely) - How likely is it that you would recommend Toyota Kenya to family, friends, colleagues?</th>
<th>Q. 5 Please tell us why you gave this score?</th>
<th>Category (NPS VOC)</th>
<th>Q. 6 Did you feel that the Sales Consultant explained the products, its features and functionality to you BEFORE purchase?	</th>
<th>Q. 7 Did the Sales Consultant who delivered your vehicle book your 1000km free service & call to remind you?	</th>
<th>Q. 8 Did the Sales Consultant brief you about the BOOK OF LIFE, including Warranty and Maintenance?</th>
<th>Q. 9 Did your Sales Advisor make a courtesy call to you after collection of your vehicle to confirm your satisfaction?	</th>
                <th>VOC Comment</th>
                <th>Type</th>
                <th>Department</th>
                <th>Comment Summary</th>
                <th>Action Required</th>
                <th>Status</th>
                <th>Staff Comments</th>
                <th>Call Date</th>
                <th>Call Time</th>
                <th>Agent</th>
                <th>Interval</th>
                <th>Call Status</th>
                <th>Disposition</th>
                <th>CallBack Date</th>
                <th>CallBack Time</th>
                <th>Call Attempts</th>
                <th>Kindly confirm if you are aware of our Toyota Kenya Toll Free line</th>
                <th>Whenever you pay a visit to our offices, are you satisfied with the measures put in place for COVID 19 protocols?</th>
                <th>Comment Box</th>

</tr>


</thead>
</table>
</div>

@push('scripts')

<script type="text/javascript">

$(document).ready(function(){

/*########################### Export Logic ##################################*/
$('#sales-report-monthly').css('display','block');
$('#sales-report-monthly').click(function(){
    $('#search-form').attr('action',"{{route('reports.sales.monthly.export',request()->id)}}");
    $('#search-form').submit();
});
/*###########################################################################*/

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var oTable=$('#monthlyReports').DataTable({
 processing: true,
 serverSide: true,
ajax:{
     url:"{{route('reports.monthly',request()->id)}}",
      type: "POST",
      "aaData":'',
        data:function(d){
            d.date_from=$('input[name=date_from]').val();
                d.date_to=$('input[name=date_to]').val();
                d.brand= $('select[name=brand]').val();
                d.branch= $('select[name=branch]').val();
                }
            },
            columns: [
            { data: 'id' },
            { data:'date_of_delivery' },
            { data:'customer'},
            { data:'customer_description'}, //contact_person
            { data:'telephone_one'},
            { data:'cust_classification'},
            { data:'license_plate_number'},
            { data:'vin_number'},
            { data:'vehicle_model'},
            { data:'new_used_vehicle'},
            { data:'name'}, 
            { data:'branch'}, 
            { data:'brand'},
            { data:'reason_for_visit'},
            { data:'order_number'},
            { data:'header_text'},
            { data:'gate_pass_ind'},
            { data:'campaign'},
            { data:'q4'},
            { data:'q5'},
            { data:'q5c'},
            { data:'q6'},
            { data:'q7'},
            { data:'q8'},
            { data:'q9'},
            { data:'voc_customer'},
            { data:'classification_type'},
            { data:'campaign'},
            { data:'classification'},
            { data:'action'},
            { data:'is_closed'},
            { data:'comments'},
            { data:'updated_at'},
            { data:'time'},
            { data:'username'},
            { data:'hour'},
            { data:'disposition_type'},
            { data:'disposition'},
            { data: 'next_call_scheduled_at'},
            { data: 'next_call_scheduled_at_time'},
            { data:'attempts'},
            { data:'aware'},
            { data:'satisfaction'},
            { data:'comment'},
            
 ],
 responsive: true,
 dom: 'lBrtip', 
 lengthMenu: [[25,50, 100,500,1000,-1], [25,50, 100,500,1000,'All']],
 pageLength: 25,
 /*order: [[ 0, "desc" ]],*/
 buttons: [],


});

$('button[type=submit]').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });

});

</script>

@endpush