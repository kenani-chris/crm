@if(!empty($call_unreachables))
<?php $customer=$call_unreachables; $call=3; ?>
    @include('outbound.'.$slug.'.questions.start.callstatus')
@else
<?php $call=3;?>
<div class="col-md-12">
<div class="alert alert-info">
    No unreachables available to call
</div>
</div>

@endif