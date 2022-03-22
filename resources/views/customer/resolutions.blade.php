@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'Resolution Rates')

@section('content')
<div class="card">
<div class="card-body">
@include('customer.search')

<div class="col-md-12"><br></div>
<div class="table-responsive leadsCards">
    <table id="resolutionRate" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
        <div class="text-right">
            <button id="resolution-rate-export" class="btn btn-primary btn-sm position-absolute export-btns" style="right: 300px !important; margin-top: 2px;"><span class="mdi mdi-microsoft-excel"></span> Export to Excel </button>
        </div>
        
        <thead>
            <tr>
                <th>#</th>
                <th>Advisor</th>
                <th>Department</th>
                <th>Branch</th>
                <th>Brand</th>
                <th>Total Cases</th>
                <th>Open Cases</th>
                <th>Closed Cases</th>
                <th>Resolution Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resolutions as $resolution)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $resolution["name"] }}</td>
                    <td>{{ $resolution["department"] }}</td>
                    <td>{{ $resolution["branch"] }}</td>
                    <td>{{ $resolution["brand"] }}</td>
                    <td>{{ $resolution["total_cases"] }}</td>
                    <td>{{ $resolution["open_cases"] }}</td>
                    <td>{{ $resolution["closed_cases"] }}</td>
                    <td>{{ $resolution["resolution_rate"] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- {{ $resolutions->links() }} --}}
</div>

@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        /*########################### Export Logic ##################################*/
        $('#resolution-rate-export').css('display','block');
        $('#resolution-rate-export').click(function(){
            $('#search-form').attr('action',"{{route('resolutions.export')}}");
            $('#search-form').submit();
        });
        $('button[type=submit]').click(function(){
            $('#search-form').attr('action',"{{route('resolutions.search')}}");
        });
    });
    $('#resolutionRate').DataTable({
        "pageLength": 15
    });
</script>
@endpush