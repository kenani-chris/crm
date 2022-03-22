@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'Followups Channel')

@section('content')
<div class="col-12">


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 section-title">
                                  
                                   
            </div>
            <div class="col-lg-12">
            @if (\Session::has('importSuccess'))
                <div class="alert alert-success">
                   {!! \Session::get('importSuccess') !!}
                </div>
            @endif
            <form action="{{route('channel.followup','channel')}}" id="newLeadUpload" method="POST" enctype="multipart/form-data">
            @csrf

                                                        <div class="form-group mt-4">

                                             <div id="msg_div">
                                                    <span class="alert alert-success d-none"  id="res_message"></span>
                                                    </div>
                                                    
                                
                                                                        <div class="alert alert-danger" style="display:none">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                                            <label>Select a channel to join</label>
                                                           
                                                            <select class="form-control" name="channel"  id="channel" required>
                                                            <option value="">Select channel</option>

                                                            <option value="1">OOC > 50%</option>
                                                            <option value="2">OOC < 50%</option>
                                                            
                                                            </select>
                                                            <span class="text-danger">{{ $errors->first('channel') }}</span>
                                                        </div>
                                                   
           
            
            <button class="btn btn-primary pull-right btn-rounded" id="joinChannel" name="joinChannel">Continue <i class="mdi mdi-arrow-right"></i> </button>
          
        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection

@push('scripts')

@endpush