<div class="modal fade" id="terminateModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">Terminate Survey </h3>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="terminateForm" action="javascript::void()">
                <input type="hidden" name="channel" value="{{isset($slug) ? $slug : ''}}" required>
        <div class="col-md-12 align-content-center">

        <p>
                                                            Select a disposition</p>

                                               @if(isset($customer))

                                                          @foreach($customer->reachable_dispositions as $disposition)
                                                            <div class="custom-control custom-radio custom-control-inline ">
                                                              <input type="radio" required id="{{$disposition->slug}}" value="{{$disposition->id}}" name="disposition" class="custom-control-input missingOle">
                                                              <label class="custom-control-label" for="{{$disposition->slug}}">{{$disposition->title}}</label>
                                                              </div>
                                                          @endforeach
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                            
                                                                <div class="form-group dispo_call mt-2" style="display:none">
                                                                <label class="control-label">What is the right time and date to call back? </label>
                                                                        <input type="datetime-local" name="dispo_call" id="dispo_call" style="padding:2px; margin-top:5px"></input>
                                                                </div>
                                                            </div>                
                                                           
                                                            <span class="text-danger">{{ $errors->first('disposition') }}</span>
                                                  @endif
                                                        </div>
                                                   
        
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-secondary btn-rounded pull-right confirmTermination">Confirm Termination</button>

        </div>
        </form>
      </div>
   
    </div>
  </div>
</div>

@push('scripts')

@if(isset($channel) && isset(request()->id))

<script type="text/javascript">
$(document).ready(function() { 
    if($("#terminateForm").length > 0){
    $("#terminateForm").validate({

        rules: {
            attendance: {
            required: true
        }
    },
        messages: {
            attendance: {
           required: "Disposition is required",
       }, 
    },
    submitHandler: function(form) {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
          loadModal("block");
        $('.confirmTermination').html('Terminating Survey...');
        $.ajax({
        url: "{{route('survey.terminate',request()->id)}}",
        type: "POST",
        data: $('#terminateForm').serialize(),
        success: function(response) {
            $('.confirmTermination').html('Terminate Survey');

            if(response.status==true){

                console.log('URL IS ',response.msg)
                sweetAlert2('Redirecting....','Successful Termination');
                loadModal("none");

                window.setTimeout(function () {
                    window.location.href=response.msg;
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

         $('.confirmTermination').html('Terminate Survey');
    
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
@endif
@endpush('scripts')