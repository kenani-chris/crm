<div id="followupModal"  class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <div class="col-md-10">
      <h4 class="modal-title"><span class="fmessage">Loading...</span> Payment Followup</h4>
      </div>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      
      </div>
      <div class="modal-body">


      
      <form method="post" id="paymentFollowupForm" action="javascript:void(0)">
      @csrf
    <div class="col-md-12">
            <div class="form-group">
                Copy and paste this number to the dialer

                <h2><span style="font-size:40px">
                
                <input type="text" value=""  name="phoneNo" id="phoneNo" style="border:none; width:300px">
                
                <sup><a href="javacsript::void()" onclick="copyNumber()" ><i class="fa fa-copy" title="Copy to clip board"></i></a></sup></span> 
                
               
            </h2> 
            </div>
            <hr>



    <div class="form-group mb-2">
   

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


<p style="line-height:40px">
<?php
                                                                $time = date("H");
                                                                $timezone = date("e");
                                                                if ($time < "12") {
                                                                    echo "Good Morning";
                                                                } else
                                                                if ($time >= "12" && $time < "17") {
                                                                    echo "Good Afternoon";
                                                                } else
                                                                if ($time >= "17" && $time < "19") {
                                                                    echo "Good Evening";
                                                                } else
                                                                if ($time >= "19") {
                                                                    echo "Good Night";
                                                                }
                                                                ?> <span class="fmessage text-bold">Loading...</span>, I am {{Auth::user()->name}} calling you from Azuri solar, as a followup for the payment of Ksh <b><span class="famount">0.00</span></b> that you promised to pay today. Kindly top up Ksh <b><span class="famount">0.00</span></b> for you to enjoy our services. What time should I expect you to make the payments?
</p>



        
            
               <div class="custom-control custom-radio custom-control-inline ">
               <input type="radio" required id="q3" value="Today" name="q3" class="custom-control-input q3Activate">
               <label class="custom-control-label" for="q3">Today</label>
               <div class="form-group q3_time" style="display:none"><label class="control-label">Time </label>
              
               <input type="time" name="q3_time" id="q3_time" style="padding:2px; margin-top:5px"></input>
           </div>    <input type="hidden" name="x_id" id="x_id" value="" required>

               </div>
               <div class="custom-control custom-radio custom-control-inline">
                   <input type="radio" required id="q3b" value="Later" name="q3" class="custom-control-input q3Activate">
                   <label class="custom-control-label" for="q3b">Later</label>
                 
                   <div class="form-group q3_date" style="display:none"><label class="control-label">Time </label>
                           <input type="date" name="q3_date" id="q3_date" style="padding:2px; margin-top:5px"></input>
                   </div>
               </div>
               <div class="custom-control custom-radio custom-control-inline">
                   <input type="radio" required id="q3c" value="Paid" name="q3" class="custom-control-input q3Activate">
                   <label class="custom-control-label" for="q3c">Paid</label>
                 
                  
               </div>
                               
              
               <span class="text-danger">{{ $errors->first('q3') }}</span>
               </div>
           </div>
      
      </div>
      <div class="modal-footer pb-4">
      <div class="col-md-12">
            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            <button class="btn btn-warning btn-rounded pull-left mr-2 followPayment">Confirm Followup <i class="fa fa-reply"></i></button>
      </div>
    
      
      </div>

      </form>
    </div>

  </div>
</div>

@push('scripts')

<script type="text/javascript">

</script>

@endpush
