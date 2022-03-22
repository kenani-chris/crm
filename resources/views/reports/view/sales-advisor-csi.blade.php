
<div class="table-responsive">
    <table id="advisorcsiReports" class="table table-centered table-nowrap table-striped table-bordered w-100" style='border-collapse: collapse;'>
        <button id="sales-advisor-csi" class="btn btn-primary btn-sm position-absolute export-btns">
            <span class="mdi mdi-microsoft-excel"></span>
            Export to Excel
        </button>
        <thead>
            <tr>
                <th>No.</th>
                <th>Service Advisor</th>
                <th>Branch</th>
                <th>Yes</th>
                <th>No</th>
                <th>Yes</th>
                <th>No</th>
                <th>Yes</th>
                <th>No</th>
                <th>Yes</th>
                <th>No</th>
                <th>Total Yes</th>
                <th>Total No</th>
                <th>Total</th>
                <th>CSI Score</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script type="text/javascript">

$(document).ready(function(){
    /*########################### Export Logic ##################################*/
    $('#sales-advisor-csi').css('display','block');
    $('#sales-advisor-csi').click(function(){
        $('#search-form').attr('action',"{{route('reports.advisorcsi.export',request()->id)}}");
        $('#search-form').submit();
    });
    /*###########################################################################*/


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var oTableAdvisorcsi=$('#advisorcsiReports').DataTable({
        processing: true,
        ajax:{
            url:"{{route('reports.advisor.csi',request()->id)}}",
            type: "POST",
            "aaData":'',
            data:function(d){
                d.date_from=$('input[name=date_from]').val();
                d.date_to=$('input[name=date_to]').val();
                d.advisor= $('select[name=advisor]').val();
                d.branch= $('select[name=branch]').val();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'advisor' },
            { data: 'branch' },
            { data: 'q6yes' },
            { data: 'q6no' },
            { data: 'q7yes' },
            { data: 'q7no' },
            { data: 'q8yes' },
            { data: 'q8no' },
            { data: 'q9yes' },
            { data: 'q9no' },
            { data: 'totalyes' },
            { data: 'totalno' },
            { data: 'total' },
            { data: 'csiscore' },
        ],

        responsive: true,
        dom: 'lBrtip', 
        lengthMenu: [[25,50, 100,500,1000,-1], [25,50, 100,500,1000,'All']],
        /*order: [[ 0, "desc" ]],*/
        buttons: [],
    });

    $('button[type=submit]').on('click', function(e) {
        oTableAdvisorcsi.ajax.reload();
        e.preventDefault();
    });
});

</script>
@endpush