@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', $channel)

@section('content')
<div class="col-12">


<div class="card">
    <div >
        <div class="row">
           

<div class="col-md-12 mt-2 mb-2">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">New Call</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Call backs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Unreachables</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    
    @include('outbound.'.$slug.'.questions.start.newcall')
    
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  <div class="col-md-12">
      @include('outbound.'.$slug.'.questions.start.callback')
    </div>
  </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
  <div class="col-md-12"> 
        @include('outbound.'.$slug.'.questions.start.unreachable')
    </div>
  </div>
</div>
</div>





        </div>
    </div>
</div>


 
                            </div> <!-- end col-->


@endsection

@push('scripts')

<script text="text/javascript">

function copyNumber() {

var add_extra="phoneNo";
    var copyPhone=document.getElementById(add_extra);
    copyPhone.select();
    copyPhone.setSelectionRange(0, 99999);
    document.execCommand("copy");

}
</script>

@endpush