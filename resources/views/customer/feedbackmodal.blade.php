<div id="feedbackModal"  class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-dark-gray">
      <div class="col-md-10">
      <h4 class="modal-title">Close Customer Feedback</h4>
      </div>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      
      </div>
      <div class="modal-body">
      <form method="post" class="row" id="closeFeedbackForm" action="javascript:void(0)">
      @csrf

      <div class="col-md-6">

      <div class="table-responsive">
        <table id="feedback" class="table oletable table-centered table-striped table-bordered " style='border-collapse: collapse; ' >
        <tbody>
        <tr>
          <td style="width:35%">Customer description</td>
          <td id="customer_description"></td>
        </tr>
        <tr>
          <td>Customer Name</td>
          <td id="customer"></td>
        </tr>
        <tr>
          <td>Customer Phone No</td>
          <td id="telephone_one"></td>
        </tr>
        <tr>
          <td>Order Number</td>
          <td id="order_number"></td>
        </tr>
        
        <tr>
          <td>Branch</td>
          <td id="branch"></td>
        </tr>

        <tr>
          <td>Brand</td>
          <td id="brand"></td>
        </tr>

        <tr>
          <td>VOC</td>
          <td id="voc_customer"></td>
        </tr>

        <tr>
          <td>Feedback type</td>
          <td id="classification_type"></td>
        </tr>




        </tbody>
        </table>



      </div>
      </div>

      <div class="col-md-6">
      <div class="form-group">
    
    <label for="comments">Comments</label>

    <textarea class="form-control" id="comments" name="comments"></textarea>

    </div>

    <div class="form-group">
    <input type="hidden" name="custid" id="custid" value="" required>
    <label>Close feedback</label>

    <select class="form-control" name="is_closed"  id="is_closed">
    <option  value="" >Choose option</option>
    <option value="1" selected>Yes</option>
    <option value="0">No</option>
    </select>
    </div>
   

   

    <div class="form-group" style="margin-top:30px">
    

    <button class="btn btn-secondary pull-right mr-2 closeFButton">Submit to close the feedback</button>

   
  </div>
    </div>
    <div class="col-md-12">
    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Exit <i class="mdi mdi-close"></i></button>

    </div>
      </div>
       
      </div>
    

      </form>
    </div>

  </div>
</div>

@push('scripts')

<script type="text/javascript">

/*$(document).ready(function() { 
 $('body').on('click', '.closeFeedback', function (event) {

event.preventDefault();

var id = $(this).data('id');

alert();
$("#custid").val(id);

$.get('/catalog/wfc/show/'+id, function (data) {
    var cat_pp=data.data.supervisor_id;
    //console.log("SSSS", cat_pp);
  
})})

*/
</script>

@endpush