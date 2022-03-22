
@if(!empty($customer))
<?php $call=1;  ?>
@include('outbound.'.$slug.'.questions.start.callstatus')

@else
<?php $call=1;?>
<div class="col-md-12">
<div class="alert alert-info">
    No leads available to call
</div>
</div>

@endif