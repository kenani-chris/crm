<div class="table-responsive">
                <table id="npsReports" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                    <button id="sales-nps-monthly" class="btn btn-primary btn-sm position-absolute export-btns"><span class="mdi mdi-microsoft-excel"></span> Export to Excel </button>
                <thead>
                <tr>
                <th>#</th>
                <th>Distributor</th>
                <th>Date</th>
                <th>VOC Source</th>
                <th>Brand</th>
                <th>Job Card Number</th>
                <th>Customer Name</th>
                <th>VIN</th>
                <th>Reg number</th>
                <th>Branch</th>
                <th>SA/Sales_Person</th>
                <th>Customer Comment</th>
                <th>Type</th>
                <th>Department</th>
                <th>Comment Summary</th>
                <th>Action Required</th>
                <th>Status</th>
                <th>Staff Comments</th>
                </tr>
                </thead>
                </table>
</div>
@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
/*########################### Export Logic ##################################*/
$('#sales-nps-monthly').css('display','block');
$('#sales-nps-monthly').click(function(){
    $('#search-form').attr('action',"{{route('reports.nps.export',request()->id)}}");
    $('#search-form').submit();
});
/*###########################################################################*/

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var oTable=$('#npsReports').DataTable({
 processing: true,
 serverSide: true,
ajax:{
     url:"{{route('reports.nps',request()->id)}}",
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
            { data: 'country' },
            { data: 'updated_at' },
            { data: 'type' },
            { data: 'brand' },
            { data: 'order_number' },
            { data: 'customer_description' },
            { data: 'vin_number' },
            { data: 'license_plate_number' },
            { data: 'branch' },
            { data: 'name' },
            { data: 'text_box_answer' },
            { data: 'classification_type' },
            { data: 'campaign' },
            { data: 'classification_name' },
            { data: 'action' },
            { data: 'is_closed' },
            { data: 'comments' }

            
 ],
 columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets:11
                }
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