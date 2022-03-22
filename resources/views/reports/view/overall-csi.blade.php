@extends('layouts.app')

@section('pageParent', 'Outbound Calls')

@section('pageTitle', 'Overall CSI Reports')
@section('content')
<div class="card">
    <div class="card-body">
        @include('reports.search')
        <div class="table-responsive">
            <table class="table table-centered table-nowrap table-striped table-bordered w-100" style='border-collapse: collapse;'>
                <div class="w-100 text-left my-2">
                    <button id="overall-csi" class="btn btn-primary btn-sm">
                        <span class="mdi mdi-microsoft-excel"></span>
                        Export to Excel
                    </button>
                </div>
                <thead>
                    <tr>
                        <th  colspan="2" class="text-center">CSI Performance</th>
                        <th>Yes</th>
                        <th>No</th>
                        <th>N/C</th>
                        <th>Hit Rate</th>
                        <th>Result</th>
                        <th>CSI Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesQuestionsCount['yes'] as $salesQuestionsCountSingle)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="4" class="text-center">
                                    Sales CSI
                                </td>
                            @endif
                            <td>
                                {{ $salesQuestions[$loop->iteration - 1] }}
                            </td>
                            <td>
                                {{ $salesQuestionsCountSingle}}
                            </td>
                            <td>
                                {{ $salesQuestionsCount['no'][$loop->iteration - 1]}}
                            </td>
                            <td>0</td>
                            <td></td>
                            <td>
                                @if (empty($salesQuestionsCountSingle))
                                    0%
                                @else
                                    {{ round($salesQuestionsCountSingle / ($salesQuestionsCountSingle + $salesQuestionsCount['no'][$loop->iteration - 1]) * 100) }}%
                                @endif
                            </td>
                            @if ($loop->first)
                                <td rowspan="4" class="text-center">
                                    @if (empty(array_sum($salesQuestionsCount['yes'])))
                                        0%
                                    @else
                                        {{ round(array_sum($salesQuestionsCount['yes']) / (array_sum($salesQuestionsCount['yes']) + array_sum($salesQuestionsCount['no'])) * 100) }}%
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    @foreach ($partsQuestionsCount['yes'] as $partsQuestionsCountSingle)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="3" class="text-center">
                                    Parts CSI
                                </td>
                            @endif
                            <td>
                                {{ $partsQuestions[$loop->iteration - 1] }}
                            </td>
                            <td>
                                {{ $partsQuestionsCountSingle}}
                            </td>
                            <td>
                                {{ $partsQuestionsCount['no'][$loop->iteration - 1]}}
                            </td>
                            <td>0</td>
                            <td></td>
                            <td>
                                @if (empty($partsQuestionsCountSingle))
                                    0%
                                @else
                                    {{ round($partsQuestionsCountSingle / ($partsQuestionsCountSingle + $partsQuestionsCount['no'][$loop->iteration - 1]) * 100) }}%
                                @endif
                            </td>
                            @if ($loop->first)
                                <td rowspan="3" class="text-center">
                                    @if (empty(array_sum($partsQuestionsCount['yes'])))
                                        0%
                                    @else
                                        {{ round(array_sum($partsQuestionsCount['yes']) / (array_sum($partsQuestionsCount['yes']) + array_sum($partsQuestionsCount['no'])) * 100) }}%
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    @foreach ($servicesQuestionsCount['yes'] as $servicesQuestionsCountSingle)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="2" class="text-center">
                                    Service CSI
                                </td>
                            @endif
                            <td>
                                {{ $servicesQuestions[$loop->iteration - 1] }}
                            </td>
                            <td>
                                {{ $servicesQuestionsCountSingle}}
                            </td>
                            <td>
                                {{ $servicesQuestionsCount['no'][$loop->iteration - 1]}}
                            </td>
                            <td>0</td>
                            <td></td>
                            <td>
                                @if (empty($servicesQuestionsCountSingle))
                                    0%
                                @else
                                    {{ round($servicesQuestionsCountSingle / ($servicesQuestionsCountSingle + $servicesQuestionsCount['no'][$loop->iteration - 1]) * 100) }}%
                                @endif
                            </td>
                            @if ($loop->first)
                                <td rowspan="2" class="text-center">
                                    @if (empty(array_sum($servicesQuestionsCount['yes'])))
                                        0%
                                    @else
                                        {{ round(array_sum($servicesQuestionsCount['yes']) / (array_sum($servicesQuestionsCount['yes']) + array_sum($servicesQuestionsCount['no'])) * 100) }}%
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    @foreach ($BPQuestionsCount['yes'] as $BPQuestionsCountSingle)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="2" class="text-center">
                                    BP CSI
                                </td>
                            @endif
                            <td>
                                {{ $BPQuestions[$loop->iteration - 1] }}
                            </td>
                            <td>
                                {{ $BPQuestionsCountSingle}}
                            </td>
                            <td>
                                {{ $BPQuestionsCount['no'][$loop->iteration - 1]}}
                            </td>
                            <td>0</td>
                            <td></td>
                            <td>
                                @if (empty($BPQuestionsCountSingle))
                                    0%
                                @else
                                    {{ round($BPQuestionsCountSingle / ($BPQuestionsCountSingle + $BPQuestionsCount['no'][$loop->iteration - 1]) * 100) }}%
                                @endif
                            </td>
                            @if ($loop->first)
                                <td rowspan="2" class="text-center">
                                    @if (empty(array_sum($BPQuestionsCount['yes'])))
                                        0%
                                    @else
                                        {{ round(array_sum($BPQuestionsCount['yes']) / (array_sum($BPQuestionsCount['yes']) + array_sum($BPQuestionsCount['no'])) * 100) }}%
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
    /*########################### Export Button Logic ##################################*/
    $('#overall-csi').click(function(){
        $('#search-form').attr('action',"{{route('reports.overallcsi.export')}}");
        $('#search-form').submit();
    });
    $('button[type=submit]').click(function(){
        $('#search-form').attr('action',"{{ route('reports.overallcsi.post') }}");
    });
    /*###########################################################################*/
});

</script>
@endpush
