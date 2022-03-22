@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel_name.' Reports')
@section('content')
<div class="card">
<div class="card-body">
@include('reports.search')
<div class="col-md-12">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="monthly-tab" data-toggle="tab" href="#csiMonthlyReport" role="tab" aria-controls="home" aria-selected="true">CSI Monthly Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="voc-tab" data-toggle="tab" href="#vocReport" role="tab" aria-controls="profile" aria-selected="false">Voice of Customer Report</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="csi-tab" data-toggle="tab" href="#csi" role="tab" aria-controls="contact" aria-selected="false">Kenya VOC Consolidated - CSI</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="nps-tab" data-toggle="tab" href="#nps" role="tab" aria-controls="contact" aria-selected="false">Kenya VOC Consolidated - NPS</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="advisor-csi-tab" data-toggle="tab" href="#advisor-csi" role="tab" aria-controls="contact" aria-selected="false">Advisor CSI</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
 @include('reports.view.'.strtolower($channel_name).'.index')

 @include('reports.view.consolidated')
</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        // events are not running
        $('#monthly-tab,#voc-tab,#csi-tab,#nps-tab').on('click',function(){
            $('#advisor-parent').fadeOut(250).promise().done(function() { 
                $('#brand-parent').fadeIn(250);
            });
        });
        $('#advisor-csi-tab').on('click',function(){
            $('#brand-parent').fadeOut(250).promise().done(function() { 
                $('#advisor-parent').fadeIn(250);
            });
        });
    });
</script>
@endpush