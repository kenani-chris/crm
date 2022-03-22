<div class="table-responsive">
<table class="table table-bordered table-striped customerInfo">
<thead>
<tr>
<th>
Date Out
</th>
<th>
Customer name
</th>
<th>
Make.model
</th>
<th>
Registration
</th>
<th>
Company Name
</th>
<th>
Mobile Number
</th>
<th>
Service Advisor
</th>
</tr>

</thead>
<tbody>

<tr>
<td>{{$customer->DateOut}}</td>
<td>{{$customer->CustomerName}}</td>
<td>{{$customer->MakeModel}}</td>
<td>{{$customer->Registration}}</td>
<td>{{$customer->CompanyName}}</td>
<td>{{$customer->MobileNumber}}</td>
<td>{{ucwords($customer->ServiceAdvisor)}}</td>
</tr>

</tbody>   
</table>  
            
</div>