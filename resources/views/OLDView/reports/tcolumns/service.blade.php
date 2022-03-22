<tr>
<th style="width: 20px;">#</th>
<th>Date Out</th>
<th>Customer name</th>
<th>Make.model</th>
<th>Registration</th>
<th>Company Name</th>
<th>Mobile Number</th>
<th>Service Advisor</th>
<th>Branch</th>
<th>Call Status</th>
<th>Q1. Introduction/Greetings</th>
<th>Q2. Is this time convenient to speak to you?</th>
<th>Q2. What is the convenient time to call you back?</th>
<th>Q3. On a scale of 0-10 (0 - Very Unlikely, 10 - Very likely) - How likely is it that you would recommend DT Dobie to family, friends, colleagues?</th>
<th>Type</th>
<th>Q3. Please tell us why you gave this score</th>
<th>Comment Type</th>
<th>Department</th>
<th>Comment Summary</th>
<th>Action required</th>
<th>Q5. Did the service advisor offer to inspect your vehicle with you before the works started?</th>
<th>Q6. Did you receive an explanation of the actual work after the service was completed?</th>
<th>Q7. Was your vehicle fixed right?</th>
<th>Q8. Were the repairs/Maintenance completed within the advised time?</th>
<th>Q9. Do you feel the time taken to service/repair your vehicle was reasonable?</th>
<th>Q9. VOC</th>
<th>Comment Type</th>
<th>Department</th>
<th>Comment Summary</th>
<th>Action required</th>
<th>Call Disposition</th>
<th>Agent</th>
<th>Call Date</th>
<th>Call Time</th>

</tr>
@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var oTable=$('#DTDOBIEData').DataTable({
 processing: true,
 serverSide: true,
ajax:{
     url:"{{route('reports.rawdata')}}",
      type: "POST",
      "aaData":'',
        data:function(d){
                d.xpath="{{request()->id}}";
                d.callStatus= $('select[name=callStatus]').val();
                d.callDisposition= $('select[name=callDisposition]').val();
                d.agent= $('select[name=agent]').val();
                d.date_from=$('input[name=date_from]').val();
                d.date_to=$('input[name=date_to]').val();
                d.completion_from=$('input[name=completion_from]').val();
                d.completion_to=$('input[name=completion_to]').val();
                }
            },
            columns: [
            { data: 'id' },
            { data: 'DateOut'},
            { data: 'CustomerName'},
            { data: 'MakeModel'},
            { data: 'Registration'},
            { data: 'CompanyName'},
            { data: 'MobileNumber'},
            { data: 'ServiceAdvisor'},
            { data: 'BranchCode1'},
            { data:'intro'},
            { data: 'q1'},
            { data: 'q2'},
            { data: 'callback'},
            { data :'q3'},
            { data :'type'},
            { data :'q3_comments'},
            { data: 'q4_comment_type_id'},
            { data: 'q4_channel_id'},
            { data: 'q4_comment_summary_id'},
            { data: 'q4_action_required'},
            { data: 'q5'},
            { data: 'q6'},
            { data: 'q7'},
            { data: 'q8'},
            { data: 'q9'},
            { data: 'q9_comments'},
            { data: 'q10_comment_type_id'},
            { data: 'q10_channel_id'},
            { data:  'q10_comment_summary_id'},
            { data: 'q10_action_required'},
            { data: 'disposition_id'},
            {data :'user_id'},
            { data: 'created_at'},
            { data: 'created_at_time'},      
 ],
 responsive: true,
 dom: 'lBrtip', 
 lengthMenu: [[25,50, 100,500,1000,2000,5000,10000,20000,50000], [25,50, 100,500,1000,2000,5000,10000,20000,50000]],
 pageLength: 50,
 order: [[ 32, "desc" ]],
 buttons: [
            {
                extend: 'excel',
                text: '<span class="mdi mdi-microsoft-excel"></span> Export to Excel',
                exportOptions: {
                   
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
                   
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                    }
                }
            }
        ],


});

$('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

});

</script>
@endpush