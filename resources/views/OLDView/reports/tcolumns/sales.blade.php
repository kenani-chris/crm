<tr>
                                                        <th style="width: 20px;">#</th>
                                                       <th>Distributor Name/OutletName</th>
                                                       <th>RetailOutlet/DealerCode</th>
                                                       <th>Title </th>
                                                       <th>Initials</th>
                                                       <th>Surname</th>
                                                       <th>Landline(work)</th>
                                                       <th>Mobile</th>
                                                       <th>Transaction Type</th>
                                                       <th>Company Name</th>
                                                       <th>Fleet/Government/Private</th>
                                                       <th>Model Code</th>
                                                       <th>Model Name</th>
                                                       <th>Registration or VIN</th>
                                                       <th>Transaction date</th>
                                                       <th>Salesperson Name</th>
                                                       
                                                       <th>Call Status</th>
                                                       <th>Q1. Introduction/Greetings</th>
                                                       <th>Q2. Is this time convenient to speak to you?</th>
                                                       <th>Call Back</th>
                                                       <th>Q3. On a scale of 0 - 10 (0-Very Unlikely, 10 -Very likely) - How likely is it that you would recommend DT Dobie {Outlet} to family, friends, colleagues?</th>
                                                       <th>Type</th>
                                                       <th>Please tell us why you gave this score?</th>
                                                       <th>Q4. Are there any aspects in our service process that you fell would help us serve you better?</th>
                                                       <th>Q4 Yes Feedback/Comments</th>
                                                       <th>Comment Type</th>
                                                       <th>Department</th>
                                                       <th>Comment Summary</th>
                                                       <th>Action required</th>
                                                       <th>Q6. Did you feel that the Sales Consultant explained the products, its features and functionality to you BEFORE purchase?</th>
                                                       <th>Q7. Was you vehicle delivered in perfect condition?</th>
                                                       <th>Q8. Did the vehicle hand over meet your expectations?</th>
                                                       <th>Q9. Did the sales executive make a courtesy call to you after collection of your vehicle to confirm your satisfaction?</th>
                                                       <th>Q10. Were you introduced to an Aftersales representative?</th>
                                                       <th>VOC</th>
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
            { data: 'DistributorNameOutletName'},
            { data: 'RetailOutletDealerCode'},
            { data: 'Title'},
            { data: 'Initials'},
            { data: 'Surname'},
            { data: 'Landline'},
            { data: 'Mobile'},
            { data: 'TransactionType'},
            { data: 'CompanyName'},
            { data: 'FleetGovernmentPrivate'},
            { data: 'ModelCode'},
            { data: 'ModelName'},
            { data: 'RegistrationVIN'},
            { data: 'TransactionDate'},
            { data: 'SalesPersonName'},
            { data:'intro'},
            { data: 'q1'},
            { data: 'q2'},
            { data: 'callback'},
            { data: 'q3'},
            { data :'type'},
            { data: 'q3_comments'},
            { data: 'q4'},
            { data: 'q4_yes_comments'},
            { data: 'q5_comment_type_id'},
            { data: 'q5_channel_id'},
            { data: 'q5_comment_summary_id'},
            { data: 'q5_action_required'},
            { data: 'q6'},
            { data: 'q7'},
            { data: 'q8'},
            { data: 'q9'},
            { data: 'q10'},
            { data: 'q10_comments'},
            { data:  'q11_comment_type_id'},
            { data: 'q11_channel_id'},
            { data: 'q11_comment_summary_id'},
            { data: 'q11_action_required' },
            { data: 'disposition_id'},
            {data :'user_id'},
            { data: 'created_at'},
            { data: 'created_at_time'},
            
 ],
 responsive: true,
 dom: 'lBrtip', 
 lengthMenu: [[25,50, 100,500,1000,2000,5000,10000,20000,50000], [25,50, 100,500,1000,2000,5000,10000,20000,50000]],
 pageLength: 50,
 order: [[ 41, "desc" ]],
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
@endpush: