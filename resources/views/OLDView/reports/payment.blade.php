@extends('layouts.app')

@section('pageParent', 'Reports')

@section('pageTitle', 'Paid Customers - '.$channel)

@section('content')

<div class="col-12">
                                <div class="card">
                                    <div class="card-body">

<form class="" role="form" method="post" id="search-form">

<div class="row">


<div class="col-sm-3">
        <div class="form-group">
            <label for="unit_serial_no">Unit Serial No</label>
            <input type="text" id="unit_serial_no" class="form-control" name="unit_serial_no">
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="customer_phone_no">Customer Phone NO</label>
            <input type="text" id="customer_phone_no" class="form-control" name="customer_phone_no">
        </div>
    </div>

   
    


   

    <div class="col-sm-3">
        <div class="form-group">
            <label for="date_from">Upload date from</label>
            <input type="text" id="date_from" class="form-control dateTimePicker" name="date_from">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" id="date_to" class="form-control dateTimePicker" name="date_to">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="completion_from">Call date</label>
            <input type="text" id="completion_from" class="form-control dateTimePicker" name="completion_from">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="completion_to">To date</label>
            <input type="text" id="completion_to" class="form-control dateTimePicker" name="completion_to">
        </div>

      
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="agent">Agent</label>
            <select class="form-control" name="agent" id="agent">
                <option value="">All</option>
                @foreach($agents as $agent)
                    <option value="{{$agent->id}}">{{$agent->name}}</option>
                @endforeach

                
            </select>
        </div>
    </div>
   

    <div class="col-sm-3">
    <label></label>
    <div class="form-group" style="margin-top:10px">
    <button type="submit" class="btn btn-primary btn-block pull-right btn-rounded"><i class="fa fa-search"></i> Filter</button>
</div>
    </div>
    


 

    

</div>

@csrf

</form>
    <!--end search-->


                                    </div>
                                 




                                <div class="card">
                                    <div class="card-body">
                                    <div class="row">
                                       
                                        <div class="table-responsive">
                                        
                                        <table id="RUHSolarPayment" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                                           
                                           
                                           
                                            <thead>

                                            <tr>
                                                        <th style="width: 20px;">
                                                            # 
                                                        </th>

                                                        <th>Account Number</th>	
                                                        <th>Unit Serial Number</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Identification Type</th>	
                                                        <th>Customer Identification	</th>
                                                        <th>Customer Phone Number</th>	
                                                        <th>Customer Phone Number Alternative	</th>
                                                        <th>Financier	</th>
                                                        <th>Payment Receiver</th>	
                                                        <th>Account Status</th>	
                                                        <th>Account Sub-Status</th>
                                                        <th>Registration Date	</th>
                                                        <th>Activation Date	</th>
                                                        <th>Last Top-Up Date	</th>
                                                        <th>Close Date	</th>
                                                        <th>E-Wallet</th>	
                                                        <th>Currency	</th>
                                                        <th>Unit Credit Expiry Date	</th>
                                                        <th>Total Top-Ups	</th>
                                                        <th>Total Credit (Days)	</th>
                                                        <th>Contract Length (Days)	</th>
                                                        <th>Remaining (Days)</th>	
                                                        <th>Days out of Credit	</th>
                                                        <th>Payment Plan Id	</th>
                                                        <th>Payment Plan Name	</th>
                                                        <th>Payment Plan Price	</th>
                                                        <th>Payment Plan Outstanding Balance</th>
                                                        <th>Amount Paid	</th>
                                                        <th>Product	</th>
                                                        <th>Product Version	</th>
                                                        <th>Customer Agent	</th>
                                                        <th>Customer Agent </th>
                                                        <th>Phone Number</th>	
                                                        <th>Customer Agent </th>
                                                        <th>Phone Number Alternative</th>	
                                                        <th>Customer Agent Manager	</th>
                                                        <th>Customer Agent Manager Phone Number	</th>
                                                        <th>Customer Agent Manager Phone Number Alternative	</th>
                                                        <th>Tier 1</th>	
                                                        <th>Installer Name	</th>
                                                        <th>Original Customer Agent	</th>
                                                        <th>Address	</th>
                                                        <th>Village	Sub-region	</th>
                                                        <th>Region	</th>
                                                        <th>Latitude</th>	
                                                        <th>Longitude</th>	
                                                        <th>Location Type</th>	
                                                        <th>Location Provider</th>	
                                                        <th>Customer Id	</th>
                                                        <th>Total Non Revenue Credit (Days)	</th>
                                                        <th>Lifetime Utilisation (%)</th>	
                                                        <th>Days Since Activation	</th>
                                                        <th>Days In Credit	</th>
                                                        <th>Last Topup Phone Number	</th>
                                                        <th>Customer Agent Id</th>
                                                        <th>Upload date</th>
                            
                                                        <th>Disposition</th>
                                                        <th>Call Date</th>
                                                        <th>Agent</th>

                                                      
                                                    </tr>
                                            </thead>
                                        </table>

        
        
        </div>

                                       
                                   
        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var oTable=$('#RUHSolarPayment').DataTable({
 processing: true,
 serverSide: true,
ajax:{
     url:"{{route('reports.payment')}}",
      type: "POST",
      "aaData":'',
        data:function(d){
                d.xpath={{request()->id}};
                d.unit_serial_no=$('input[name=unit_serial_no]').val();
                d.customer_phone_no=$('input[name=customer_phone_no]').val();
                d.agent= $('select[name=agent]').val();
                d.date_from=$('input[name=date_from]').val();
                d.date_to=$('input[name=date_to]').val();
                d.completion_from=$('input[name=completion_from]').val();
                d.completion_to=$('input[name=completion_to]').val();
                }
            },
            columns: [
            { data: 'id' },
            { data:'AccountNumber'},
            { data:'UnitSerialNumber'},
            { data:'CustomerName'},
            { data:'CustomerIdentificationType'},
            { data:   'CustomerIdentification'},
            { data:   'CustomerPhoneNumber'},
            { data:   'CustomerPhoneNumberAlternative'},	
            { data:   'Financier'},
            { data:   'PaymentReceiver'},
            { data:    'AccountStatus'},
            { data:   'AccountSubStatus'},
            { data:   'RegistrationDate'},
            { data:   'ActivationDate'},
            { data:   'LastTopUpDate'},
            { data:   'CloseDate'},
            { data:   'EWallet'},
            { data:  'Currency'},
            { data:   'UnitCredit'},
            { data:   'ExpiryDate'},
            { data:   'TotalTopUps'},
            { data:    'TotalCreditDays'},
            { data:   'ContractLengthDays'},
            { data:   'RemainingDays'},
            { data:   'DaysoutofCredit'},
            { data:  'PaymentPlanId'},
            { data:    'PaymentPlanName'},
            { data:   'PaymentPlanPrice'},
            { data:  'PaymentPlanOutstandingBalance'},	
            { data:    'AmountPaid'},
            { data:   'Product'},
            { data: 'ProductVersion'},
            { data:   'CustomerAgent'},
            { data:'CustomerAgentPhoneNumber' },
            { data: 'CustomerAgentPhoneNumberAlternative'},
            { data:  'CustomerAgentManager'},
            { data: 'CustomerAgentManagerPhoneNumber'},
            { data:  'CustomerAgentManagerPhoneNumberAlternative'},
            { data:  'Tier1'},
            { data: 'InstallerName'},
            { data:  'OriginalCustomerAgent'},
            { data:  'Address'},
            { data:  'Village'},
            { data:  'SubRegion'},
            { data:  'Region'},
            { data:  'Latitude'},	
            { data:   'Longitude'},
            { data:  'LocationType'},
            { data:   'LocationProvider'},
            { data:  'CustomerId'},
            { data: 'TotalNonRevenueCreditDays'},
            { data:  'LifetimeUtilisation'},
            { data:  'DaysSinceActivation'},
            { data:  'DaysInCredit'},	
            { data:  'LastTopupPhoneNumber'},
            { data:   'CustomerAgentId'},
            { data:   'upload_date'},
            { data:  'disposition'},
            { data:  'created_at'},
            { data:  'agent'}
 ],
 responsive: true,
 dom: 'lBrtip', 
 lengthMenu: [[25,50, 100,500,1000,2000,5000,10000,20000,50000], [25,50, 100,500,1000,2000,5000,10000,20000,50000]],
 pageLength: 50,
 order: [[ 57, "desc" ]],
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