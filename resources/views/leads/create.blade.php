@extends('layouts.app')

@section('pageParent', 'Leads')

@section('pageTitle', 'Upload Leads')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 section-title">
                    <p>Bulk Import of leads from csv</p>
                    @if(session('leads-uploaded-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! session('leads-uploaded-success') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('leads-pfno-entries'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! session('leads-pfno-entries') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    <form action="{{ route('leads.store') }}" id="newLeadUpload" method="POST" enctype="multipart/form-data">
                    @csrf
                        <!-- Date View -->
                        <div class="form-group">
                            
                            <label>Select channel to upload</label>
                            
                            <select class="form-control" name="channel" required="required" id="channel">
                                <option disabled selected value="">Select channel</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{$campaign->id}}" {{($campaign->name=="Service" || $campaign->name=="Sales" ||  $campaign->name=="Body Shop" ||  $campaign->name=="Parts") ? "" : "disabled"}}>{{$campaign->name}}</option>
                                @endforeach
                            </select>
                        </div>
                                                        
                        <div class="form-group mb-4">
                            <label for="catalogFile">Select upload file</label>
                            <div class="custom-file text-left">
                                <input type="file" class="custom-file-input" id="catalogFile" name="leads_csv" required>
                                <label class="custom-file-label" for="catalogFile">Choose file</label>
                            </div>
                            @error('leads_csv')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary btn-rounded" type="submit">Upload Leads <i class="mdi mdi-upload"></i> </button>
                            <a href="{{ route('leads.download.template') }}" class="btn btn-primary btn-rounded" >Download Leads Template <i class="mdi mdi-download"></i> </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end col-->
@endsection
