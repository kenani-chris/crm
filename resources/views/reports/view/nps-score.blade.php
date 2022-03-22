@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'NPS Score Reports')
@section('content')
<div class="card">
    <div class="card-body">
        @include('reports.search')
        <div class="table-responsive">
            <table class="table table-centered table-nowrap table-striped table-bordered w-100" style='border-collapse: collapse;'>
                <div class="w-100 text-left my-2">
                    <button id="nps-score-export" class="btn btn-primary btn-sm">
                        <span class="mdi mdi-microsoft-excel"></span>
                        Export to Excel
                    </button>
                </div>
                @foreach ($campaigns as $campaign)
                    <thead>
                        <tr>
                            <th  colspan="5" class="text-center">{{ $campaign->name }} NPS Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Promoter</td>
                            <td>{{ $nps['promoter'][$campaign->slug] }}</td>
                            <td rowspan="3">
                                @php
                                    $total = $nps['detractor'][$campaign->slug] + $nps['passive'][$campaign->slug] + $nps['promoter'][$campaign->slug];
                                @endphp
                                {{ $total }}
                            </td>
                            <td>
                                @if (empty($total))
                                    0.0
                                @else
                                    {{ round($nps['promoter'][$campaign->slug]/$total * 100, 1) }}
                                @endif
                            </td>
                            <td rowspan="3">
                                @if (empty($total))
                                    0.0
                                @else
                                    {{ round((($nps['promoter'][$campaign->slug]/$total)*100)-(($nps['detractor'][$campaign->slug]/$total)*100), 1) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Passive</td>
                            <td>{{ $nps['passive'][$campaign->slug] }}</td>
                            <td>
                                @if (empty($total))
                                    0.0
                                @else
                                    {{ round($nps['passive'][$campaign->slug]/$total * 100, 1) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Detractor</td>
                            <td>{{ $nps['detractor'][$campaign->slug] }}</td>
                            <td>
                                @if (empty($total))
                                    0.0
                                @else
                                    {{ round($nps['detractor'][$campaign->slug]/$total * 100, 1) }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                @endforeach
                
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
    /*########################### Export Button Logic ##################################*/
    $('#nps-score-export').click(function(){
        $('#search-form').attr('action',"{{route('reports.npsscore.export')}}");
        $('#search-form').submit();
    });
    $('button[type=submit]').click(function(){
        $('#search-form').attr('action',"{{ route('reports.npscall.post') }}");
    });
    /*###########################################################################*/
});

</script>
@endpush
