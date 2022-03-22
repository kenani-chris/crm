@extends('layouts.app')

@section('pageParent', 'Users')

@section('pageTitle', 'Registered Users')

@section('content')
                          
                                <div class="card">
                                    <div class="card-body">
                                       
                
                                        <div class="table-responsive">


                                        <table id="userTable" class="table table-centered table-nowrap table-striped table-bordered" style='border-collapse: collapse;'>
                                            <thead>
                                                    <tr>
                                                        <th style="width: 20px;">
                                                            #
                                                        </th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th style="width: 85px;">Action</th>
                                                    </tr>
                                            </thead>
                                        </table>


                                        </div>
                                        
                                    

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                          
                       
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.min.css')}}"/>
<script type="text/javascript" src="{{asset('assets/js/datatables.min.js')}}"></script>
<script type="text/javascript">

$(document).ready(function(){
    // DataTable
    $('#userTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{route('list.getUsers')}}",
         columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            {data: "action",
                "render": function(data, type, row, meta){
              if(type === 'display'){
                data = '<a href="' + data + '" class="btn btn-danger btn-sm"><i class="mdi mdi-pencil"></i> View</a>';
              }

            return data;
         }
      }
         ],
         responsive: true,
            lengthMenu: [[25,50, 100,500,1000,-1], [25,50, 100,500,1000,'All']],
            pageLength: 25,
         "columnDefs": [
            { "orderable": false, "targets": 0 }
            ]
      });

});

</script>
@endpush