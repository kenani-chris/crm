@extends('layouts.app')

@section('pageParent', 'Reports')

@section('pageTitle', 'Calls Raw Data - '.$channel)

@section('content')

<div class="col-12">
                                <div class="card">
                                    <div class="card-body">

<form class="" role="form" method="post" id="search-form">

<div class="row">


 

    <div class="col-sm-3">
    <div class="form-group">
            <label for="callStatus">Call Status</label>
            <select class="form-control" name="callStatus" id="callStatus">
                <option value="">All</option>

                @foreach($disposition_type as $dispo_type)
                    <option value="{{$dispo_type->id}}">{{$dispo_type->title}}</option>
                @endforeach
                
            </select>
        </div> 
    </div>
    

    <div class="col-sm-3">
    <div class="form-group">
            <label for="callDisposition">Call Disposition</label>
            <select class="form-control" name="callDisposition" id="callDisposition">
                <option value="">All</option>
                @foreach($disposition as $dispo)
                    <option value="{{$dispo->id}}">{{$dispo->title}}</option>
                @endforeach
            </select>
        </div> 
    </div>

   

    <div class="col-sm-3">
        <div class="form-group">
            <label for="date_from">Upload date from</label>
            <input type="text" id="date_from" class="form-control dateTimePicker" name="date_from">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" id="date_to" class="form-control dateTimePicker" name="date_to">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="completion_from">Call date</label>
            <input type="text" id="completion_from" class="form-control dateTimePicker" name="completion_from">
        </div>
    </div>

    <div class="col-sm-3">
        <div class="form-group">
            <label for="completion_to">To date</label>
            <input type="text" id="completion_to" class="form-control dateTimePicker" name="completion_to">
        </div>

      
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="agent">Agent</label>
            <select class="form-control" name="agent" id="agent">
                <option value="">All</option>
                @foreach($agents as $agent)
                    <option value="{{$agent->id}}">{{$agent->name}}</option>
                @endforeach
                
            </select>
        </div>
    </div>
    

    <div class="col-sm-3">
    <label></label>
    <div class="form-group" style="margin-top:10px">
    <button type="submit" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> Filter</button>
</div>
    </div>
    


 

    

</div>

@csrf

</form>
    <!--end search-->


                                    </div>
                                 




                                <div class="card">
                                    <div class="card-body">
                                    <div class="row">
                                       
                                        <div class="table-responsive">
                                        
                                        <table id="DTDOBIEData" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                                           
                                            <thead>
                                            @include('reports.tcolumns.'.$slug)
                                            </thead>
                                        </table>

        
        
        </div>

                                       
                                   
        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
@endsection