@extends('layouts.app')

@section('pageParent', 'Leads')

@section('pageTitle', 'Leads Tracker')

@section('content')
<div class="card leadsCards">
    <div class="card-body">
        @include('leads.leads-tracker-search')
            <div class="table-responsive">
                <table id="resolutionRate" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                    <div class="w-100 text-right my-2">
                        <button id="lead-tracker-export" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-microsoft-excel"></span>
                            Export to Excel
                        </button>
                    </div>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>No of Leads</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $leadsTrackers as $leadsTracker)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $leadsTracker->created_at }}</td>
                                <td>{{ $leadsTracker->leadcount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $leadsTrackers->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
    /*########################### Export Button Logic ##################################*/
    $('#lead-tracker-export').click(function(){
        $('#search-form').attr('action',"{{route('leads.tracker.export')}}");
        $('#search-form').submit();
    });
    $('button[type=submit]').click(function(){
        $('#search-form').attr('action',"{{ route('leads.tracker.post') }}");
    });
    /*###########################################################################*/
});

</script>
@endpush