@if(!empty($customer_callback))
<?php $customer=$customer_callback; $call=2; ?>
    @include('outbound.'.$slug.'.questions.start.callstatus')
@else
<?php $call=2; ?>
<div class="col-md-12">
<div class="alert alert-info">
    No call backs available to call
</div>
</div>

@endif