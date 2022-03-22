<div class="table-responsive">
<table class="table table-bordered table-striped customerInfo">
<thead>
<tr>
<th>
Distributor Name/OutletName
</th>
<th>
RetailOutlet/DealerCode
</th>
<th>
Title 
</th>
<th>
Initials 
</th>
<th>
Surname
</th>
<th>
Landline(work)
</th>
<th>
Mobile
</th>
<th>
Transaction Type
</th>
<th>
Company Name
</th>
<th>
Fleet/Government/Private
</th>
<th>
Model Code
</th>
<th>
Model Name
</th>
<th>
Registration or VIN
</th>
<th>
Transaction date
</th>
<th>
Salesperson Name
</th>

</tr>


</thead>
<tbody>

<tr>
<td>{{$customer->DistributorNameOutletName}}</td>
<td>{{$customer->RetailOutletDealerCode}}</td>
<td>{{$customer->Title}}</td>
<td>{{$customer->Initials}}</td>
<td>{{$customer->Surname}}</td>
<td>{{$customer->Landline}}</td>
<td>{{$customer->Mobile}}</td>
<td>{{$customer->TransactionType}}</td>
<td>{{$customer->CompanyName}}</td>
<td>{{$customer->FleetGovernmentPrivate}}</td>
<td>{{$customer->ModelCode}}</td>
<td>{{$customer->ModelName}}</td>
<td>{{$customer->RegistrationVIN}}</td>
<td>{{$customer->TransactionDate}}</td>
<td>{{$customer->SalesPersonName}}</td>
</tr>

</tbody>   
</table>  
            
</div>