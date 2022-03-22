@php
    $campaignSlug = \App\Models\Campaign::select('slug')->where('id',request()->id)->first()->slug;
@endphp
@if ($campaignSlug == 'sales')
    @include('reports.view.sales-advisor-csi')
@else
    @include('reports.view.others-advisor-csi')
@endif