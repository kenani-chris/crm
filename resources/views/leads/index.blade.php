@extends('layouts.app')

@section('pageParent', 'Leads')

@section('pageTitle', 'Leads')

@section('content')
<div class="card leadsCards">
    <div class="card-body">
        @include('leads.search')
        <ul class="nav nav-pills nav-justified mt-2 leads-table-links">
            <li class="nav-item mr-1">
                @if (isset($callback) || isset($unreachable))
                    <a class="nav-link hover-active" href="{{ route('leads.user') }}">New Call</a>
                @else
                    <a class="nav-link active">New Call</a>
                @endif
            </li>
            <li class="nav-item">
                @if (isset($callback))
                    <a class="nav-link active">Call backs</a>
                @else
                    <a class="nav-link hover-active" href="{{ route('leads.user.callback') }}">Call backs</a>
                @endif
            </li>
            <li class="nav-item ml-1">
                
                @if (isset($unreachable))
                    <a class="nav-link active">Unreachables</a>
                @else
                    <a class="nav-link hover-active" href="{{ route('leads.user.unreachable') }}">Unreachables</a>
                @endif
            </li>
        </ul>
        <div class="col-md-12"><br></div>
            <div class="table-responsive">
                <table id="resolutionRate" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Telephone 1</th>
                            <th>Telephone 2</th>
                            @if (isset($unreachable))
                                <th>Last Contacted by</th>
                                <th>Contacted On</th>
                            @elseif(isset($callback))
                                <th>Last Contacted by</th>
                                <th>CallBack Date</th>
                                <th>CallBack Time</th>
                            @else
                                <th>Channel</th>
                                <th>Branch</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->customer }}</td>
                                <td>{{ $contact->telephone_one }}</td>
                                <td>{{ $contact->telephone_two }}</td>
                                @if (isset($unreachable))
                                    <td>
                                        {!! !empty($contact->member->first()->user_id) ? \App\Models\User::findOrFail($contact->member->first()->user_id)->name : '<span class="text-warning">Not Contacted</span>' !!}
                                    </td>
                                    <td>
                                        @isset($contact->member->first()->last_called_at)
                                            {{ $contact->member->first()->last_called_at }}
                                        @endisset
                                        @empty($contact->member->first()->last_called_at)
                                            <span class="text-warning">Not Contacted</span>
                                        @endempty
                                    </td>
                                @elseif(isset($callback))
                                    <td>
                                        {!! !empty($contact->member->first()->user_id) ? \App\Models\User::findOrFail($contact->member->first()->user_id)->name : '<span class="text-warning">Not Contacted</span>' !!}
                                    </td>
                                    <td>
                                        {{-- @if ($contact->member->first()->next_call_scheduled_at >= now()) --}}
                                            {{ \Carbon\Carbon::parse($contact->member->first()->next_call_scheduled_at)->format('m/d/Y') }}
                                        {{-- @endif --}}
                                    </td>
                                    <td>
                                        {{-- @if ($contact->member->first()->next_call_scheduled_at >= now()) --}}
                                            {{ \Carbon\Carbon::parse($contact->member->first()->next_call_scheduled_at)->format('h:i a') }}
                                        {{-- @endif --}}
                                    </td>
                                @else
                                    <td>{!! !empty($contact->member->first()->campaign_id) ? \App\Models\Campaign::findOrFail($contact->member->first()->campaign_id)->name : '<span class="text-warning">N/A</span>' !!}</td>
                                    <td>{!! !empty($contact->member->first()->branch_id) ? \App\Models\Branch::findOrFail($contact->member->first()->branch_id)->name : '<span class="text-warning">N/A</span>' !!}</td>
                                @endif
                                <td>
                                    <a href="{{ route('leads.call',$contact) }}" class="btn btn-info btn-sm"><i data-feather="phone" style="width:1rem;"></i> CALL</a>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>

@endsection