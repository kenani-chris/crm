<div class="table-responsive">
<table class="table table-bordered table-striped customerInfo">
<thead>
<tr>
<th>
Unit serial no
</th>
<th>
Customer name
</th>
<th>
Customer phone no
</th>
<th>
Alt Customer phone no
</th>
<th>
Financier
</th>
<th>
E-Wallet
</th>
<th>
Rem Days
</th>
<th>
Days out of credit
</th>
<th>
Payment plan name
</th>
<th>
Payment plan price
</th>
<th>
Outstanding Balance
</th>
<th>
Amount Paid
</th>
<th>
Customer agent
</th>
<th>
Agent Phone no
</th>
<th>
Tier 1
</th>
<th>
Lifetime utilisation %
</th>
<th>
Last topup Phone no
</th>

</tr>

</thead>
<tbody>

<tr>
<td>
{{$customer->UnitSerialNumber}}
</td>
<td>
{{$customer->CustomerName}}
</td>
<td>
{{$customer->CustomerPhoneNumber}}
</td>
<td>
{{$customer->CustomerPhoneNumberAlternative}}
</td>
<td>
{{$customer->Financier}}
</td>
<td>
{{$customer->EWallet}}
</td>
<td>
{{$customer->RemainingDays}}
</td>
<td>
{{$customer->DaysoutofCredit}}
</td>
<td>
{{$customer->PaymentPlanName}}
</td>
<td>
{{$customer->PaymentPlanPrice}}
</td>
<td>
{{$customer->PaymentPlanOutstandingBalance}}
</td>
<td>
{{number_format($customer->AmountPaid,2)}}
</td>
<td>
{{$customer->CustomerAgent}}
</td>
<td>
{{$customer->CustomerAgentPhoneNumber}}
</td>
<td>
{{$customer->Tier1}}
</td>
<td>
{{$customer->LifetimeUtilisation}}%
</td>
<td>
{{$customer->LastTopupPhoneNumber}}
</td>

</tr>

</tbody>   
</table>  
            
</div>