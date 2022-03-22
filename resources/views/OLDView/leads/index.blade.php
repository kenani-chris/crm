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
                                   
            </div>
            <div class="col-lg-12">
            @if (\Session::has('importSuccess'))
                <div class="alert alert-success">
                   {!! \Session::get('importSuccess') !!}
                </div>
            @endif
            <form action="javascript::void()" id="newLeadUpload" method="POST" enctype="multipart/form-data">
            @csrf

          

           

                                                        <!-- Date View -->
                                                        <div class="form-group">

                                             <div id="msg_div">
                                                    <span class="alert alert-success d-none"  id="res_message"></span>
                                                    </div>
                                                    
                                
                                                                        <div class="alert alert-danger" style="display:none">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                                            <label>Select channel to upload</label>
                                                           
                                                            <select class="form-control" name="channel"  id="channel" required>
                                                            <option value="">Select channel</option>

                                                            @foreach($channels as $channel)
                                                                <option value="{{$channel->id}}" {{($channel->title=="Service" || $channel->title=="Sales" ||  $channel->title=="Body Shop") ? "" : "disabled"}}>{{$channel->title}}</option>
                                                            @endforeach
                                                            
                                                            
                                                            </select>
                                                            <span class="text-danger">{{ $errors->first('channel') }}</span>
                                                        </div>
                                                   
            <div class="form-group mb-4">
            <label>Select upload file</label>

                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="oocFile" required>
                    <label class="custom-file-label" for="catalogFile">Choose file</label>
                </div>
            </div>
            
            <button class="btn btn-primary btn-rounded" id="uploadLeads" name="uploadLeads">Upload Leads <i class="mdi mdi-upload"></i> </button>
          
        </form>

            </div>
        </div>
    </div>
</div>


 
                            </div> <!-- end col-->
@endsection

@push('scripts')

<script type="text/javascript">

$('#oocFile').on('change',function(){
    
        var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
    })

    $('#newLeadUpload').on('submit', function (e) {
        e.preventDefault();

        $('.alert-danger').html('');

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      loadModal("block");
      $('#uploadLeads').html('Uploading leads..');

      var formData = new FormData(this);

      $.ajax({
        url: "{{route('upload.leads')}}" ,
        type: "POST",
        data: formData,
        success: function( response ) {
            $('#uploadLeads').html('Upload Leads <i class="mdi mdi-upload"></i> ');
            //$('#res_message').show();
            //$('#res_message').html(response.msg);
            //$('#msg_div').removeClass('d-none');
            loadModal("none");
            sweetAleart(response.msg);
            document.getElementById("newLeadUpload").reset(); 
            setTimeout(function(){
            $('#res_message').hide();
            $('#msg_div').hide();
            },10000);
        },
        error: function(request, status, error ) {

            $('#uploadLeads').html('Upload Leads <i class="mdi mdi-upload"></i> ');

            loadModal("none");
            json = $.parseJSON(request.responseText);
            $.each(json.errors, function(key, value){
                $('.alert-danger').show();
                $('.alert-danger').append(value);
            });
            $("#re_message").html('');
            setTimeout(function(){
            $('.alert-danger').hide();
            },10000);

        },
        cache: false,
        contentType: false,
        processData: false
      });




    });

function sweetAleart(msg){

swal({
    title: "Success",
    text: msg,
    button: "Close", // Text on button
    icon: "success", //built in icons: success, warning, error, info
    timer: 3000, //timeOut for auto-close
     buttons: {
       confirm: {
         text: "Close",
         value: true,
         visible: true,
         className: "",
         closeModal: true
       },
       cancel: {
         text: "Cancel",
         value: false,
         visible: true,
         className: "",
         closeModal: true,
       }
     }
    });
}
</script>

@endpush