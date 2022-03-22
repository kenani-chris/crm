
<div class="modal fade" id="attendanceModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">{{date('l jS \of F Y')}} attendance </h3>
       
      </div>
      <div class="modal-body">
        <form method="post" id="attendanceForm" action="javascript::void()">
@csrf
       
        <h4>
        @if(date('A')=="PM")
                Good Afternoon
        @elseif(date('A')=="AM")
               Good Morning
        @else
            Good Evening
        @endif
        , <span class="text-primary">{{ucwords(Auth::user()->name)}}</span>!
        </h4>


        <div class="col-md-12 align-content-center">

        <div class="form-group mt-3">
        <label class="control-label">Please select your attendance status below </label>


        <div class="custom-control custom-radio custom-control-inline">
                                              <input type="radio" id="attendance1" value="Present" name="attendance" class="custom-control-input">
                                              <label class="custom-control-label" for="attendance1">Present</label>
                                          </div>
                                          <div class="custom-control custom-radio custom-control-inline">
                                              <input type="radio" id="attendance2" value="Absent" name="attendance" class="custom-control-input">
                                              <label class="custom-control-label" for="attendance2">Absent</label>
                                          </div>


                                          </div>
        
        </div>
        <button type="submit" class="btn btn-secondary pull-right markAttendance">Mark Attendance</button>

        </form>
      </div>
   
    </div>
  </div>
</div>

@push('scripts')

<script type="text/javascript">
$(document).ready(function() { 
    if($("#attendanceForm").length > 0){
    $("#attendanceForm").validate({

        rules: {
            attendance: {
            required: true
        }
    },
        messages: {
            attendance: {
           required: "Attendance status is required",
       }, 
    },
    submitHandler: function(form) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        loadModal("block");
        $('.markAttendance').html('Submitting..');
        $.ajax({
        url: "{{route('attendance.mark')}}",
        type: "POST",
        data: $('#attendanceForm').serialize(),
        success: function(response) {
            $('.markAttendance').html('Mark Attendance');

            if(response.status==true){
                sweetAlert2('Success',response.msg);
                loadModal("none");

                window.setTimeout(function () {
                window.location.reload();
                }, 2000);


            }else{
                sweetAlert2('Error',response.msg);
                loadModal("none");
            }



        },error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
         var errorMessage=JSON.parse(xhr.responseText);
         sweetError2(errorMessage.errors);

         loadModal("none");

         $('.markAttendance').html('Mark Attendance');
    
     }
        });
        }

    })
}



function sweetAlert2(msg,type){
     swal({
        title:type,
        text:msg,
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

function sweetError2(msg){
     swal({
        title: "Error",
        text:msg,
        button: "Close", // Text on button
        icon: "error", //built in icons: success, warning, error, info
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
});
</script>
@endpush('scripts')