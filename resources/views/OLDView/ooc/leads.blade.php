@extends('layouts.app')

@section('pageParent', 'Leads')

@section('pageTitle', $name)

@section('content')

<div class="col-12">
                                <div class="card">
                                    <div class="card-body">

<form class="" role="form" method="post" id="search-form">

<div class="row">



   
    <div class="col-sm-4">
        <div class="form-group">
            <label for="date_from">Upload Date From</label>
            <input type="text" id="date_from" class="form-control dateTimePicker" name="date_from">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" id="date_to" class="form-control dateTimePicker" name="date_to">
        </div>
    </div>

   

   


  
    <div class="col-sm-4">
<label></label>
    <div class="form-group" style="margin-top:7px">
    <button type="submit" class="btn btn-success btn-block pull-right"><i class="fa fa-search"></i> Search</button>
</div>
    </div>

    

</div>

@csrf

</form>
    <!--end search-->


                                    </div>
                                    </div>


<div class="col-12">


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 section-title">
                                    <p>{{$name}} Leads</p>
                                   
            </div>
            <div class="col-lg-12">

            <div class="table-responsive">
                                        
                                        <table id="leadsTable" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                                           
                                           
                                           
                                            <thead>
                                            <tr>
                                                        <th style="width: 20px;">
                                                            # 
                                                        </th>
                                                        <th>Unit Serial No</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone No</th>
                                                        <th>Customer Phone No Alt</th>
                                                        <th>Financier</th>
                                                        <th>
                                                           Current Disposition
                                                        </th>
                                                        <th>
                                                            Last Update
                                                        </th>
                                                        <th>
                                                            Date Uploaded
                                                        </th>
                                                       
                                                        <th>Action</th>
                                                    </tr>
                                            </thead>
                                        </table>


                                        </div>
           
           

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection

@push('scripts')

<script type="text/javascript">
 $(document).ready(function(){

var oTable=$('#leadsTable').DataTable({
   processing: true,
   serverSide: true,
   ajax:{
       url:"{{route('leads.show',request()->id)}}",
       data:function(d){
           d.xpath="{{request()->id}}";
           d.date_from=$('input[name=date_from]').val();
           d.date_to=$('input[name=date_to]').val();
       }
      },  
   columns: [
      { data:'id' },
      { data:'UnitSerialNumber'},
      { data:'CustomerName'},
      { data:'CustomerPhoneNumber' },
      { data:'CustomerPhoneNumberAlternative'},
      { data:'Financier'},
      { data:'lastDisposition'},
      { data:'updated_at'},
      { data:'created_at'},
      { data: "action",
          "render": function(data, type, row, meta){
            if(type === 'display'){
                data = '<a href="' + data + '" class="btn btn-secondary btn-sm"><i class="mdi mdi-pen-plus"></i> Action</a>';
            }
      return data;
   }
}
   ],
   responsive: true,
   dom: 'lBrtip', 
   lengthMenu: [[25,50, 100,500,1000,5000,10000, -1], [25,50, 100,500,1000,5000,10000, "All"]],
   pageLength: 25,
   order: [[ 6, "desc" ]],
   buttons: [
              {
                  extend: 'excel',
                  text: '<span class="mdi mdi-microsoft-excel"></span> Export to Excel',
                  exportOptions: {
                      columns: [1,2,3,4,5,6],
                      modifier: {
                          search: 'applied',
                          order: 'applied',
                      }
                  }
              },
              
              {
                  extend: 'csv',
                  text: '<span class="mdi mdi-file-delimited-outline"></span> Export to CSV',
                  exportOptions: {
                      columns: [1,2,3,4,5,6],
                      modifier: {
                          search: 'applied',
                          order: 'applied',
                      }
                  }
              },
              {
                  extend: 'copy',
                  text: '<span class="mdi mdi-content-copy"></span> Copy',
                  exportOptions: {
                      columns: [1,2,3,4,5,6],
                      modifier: {
                          search: 'applied',
                          order: 'applied',
                      }
                  }
              },
              {
                  extend: 'print',
                  text: '<span class="fa fa-print"></span> Print',
                  exportOptions: {
                      columns: [1,2,3,4,5,6],
                      modifier: {
                          search: 'applied',
                          order: 'applied',
                      }
                  }
              }
          ],
});

$('#search-form').on('submit', function(e) {
          oTable.draw();
          e.preventDefault();
      });

});

</script>

@endpush