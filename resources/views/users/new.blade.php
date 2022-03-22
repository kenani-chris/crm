@extends('layouts.app')

@section('pageParent', 'Users')

@section('pageTitle', 'New User')

@section('content')

        
            <div class="card ">
            <h4 class="pl-3 pb-2 pt-2" >Create User</h4>
            <div style="border-bottom:1px solid #ccc"></div>
           
                <div class="card-body">

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                    <form method="POST"  id="userForm" action="javascript:void(0)">
                    <div class="alert alert-success d-none" id="msg_div">
                                                    <span id="res_message"></span>
                                                    </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">

                                <div class="col-6">
                        <div class="form-group">
                            <label for="name" class="col-form-label ">{{ __('Name') }}</label>

                           
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>

                        <div class="form-group">
                            <label for="email" class=" col-form-label ">{{ __('E-Mail') }}</label>

                         
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class=" col-form-label ">{{ __('Phone') }}</label>

                        
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" required>

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="pf_no" class=" col-form-label ">{{ __('PF No') }}</label>

                        
                                <input id="pf_no" type="text" class="form-control @error('pf_no') is-invalid @enderror" name="pf_no">

                                @error('pf_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="role_id" class=" col-form-label ">{{ __('User Role') }}</label>

                        

                                <select class="form-control @error('role_id') is-invalid @enderror" data-toggle="role_id" name="role_id" id="role_id" required>
                                <option value="">Select role</option>
                                @foreach($roles as $myrole)
                                <option value="{{$myrole->id}}" >{{$myrole->name}}</option>
                                      @endforeach  
                                </select>

                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        </div>

                        <div class="col-6">
                     

                        <div class="form-group">
                            <label for="branch_id" class="col-form-label ">{{ __('Branch') }}</label>

                        

                                <select class="form-control @error('branch_id') is-invalid @enderror" data-toggle="branch_id" name="branch_id" id="branch_id" required>
                                <option value="">Select branch</option>
                                @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" >{{$branch->name}}</option>
                                      @endforeach  
                                </select>

                                @error('branch_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="brand" class="col-form-label ">{{ __('Brands') }}</label>

                        

                        
                                <select style="height" class="multiselect-ui form-control @error('brand') is-invalid @enderror" multiple="multiple" name="brand[]" id="brand" required >
                             
                                @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                               
                                @endforeach
                                </select>


                                @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="campaign_id" class="col-form-label ">{{ __('Department') }}</label>

                        

                        
                                <select style="height" class="form-control @error('campaign_id') is-invalid @enderror" name="campaign_id" id="campaign_id" required >
                                <option value="">Select</option>
                                @foreach($campaigns as $campaign)
                                <option value="{{$campaign->id}}">{{$campaign->name}}</option>
                               
                                @endforeach
                                </select>


                                @error('campaign_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class=" col-form-label ">{{ __('Password') }} <sup><em>Default password is toyota@2021</em></sup></label>

                         
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="toyota@2021"  required >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>



                     

                        <div class="form-group mb-0">
                           
                              
                                <button type="submit" class="btn btn-secondary pull-right waves-effect waves-light createUser">Add new user <i class="mdi mdi-account-edit mr-1"></i> </button>

                           
                        </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
    

       
</div>
@endsection

@push('scripts_2')

@endpush

@push('scripts')


<script type="text/javascript">


if ($("#userForm").length > 0) {
    $("#userForm").validate({

     
     
    rules: {
      name: {
        required: true,
        minlength: 3
      },
      password:{
        required:true,
        minlength:6
      },
      role_id:{
          required:true,
      },
      phone_number:{
          required:false
      },
      branch_id:{
          required:true,
      },
      brand:{
          required:true,
      },
      campaign_id:{
          required:true,
      },
      email: {
            required: true,
            maxlength: 100,
            email:true
        },

     
    },
    messages: {
       
        
    },
    submitHandler: function(form) {

      

    
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('.createUser').html('Adding new user..');
      $.ajax({
        url: "{{route('new.user.create')}}" ,
        type: "POST",
        data: $('#userForm').serialize(),
        success: function( response ) {


            $('.createUser').html('Add new user');
            $('#res_message').show();
            $('#res_message').html(response.msg);

            if(response.status===true){
                $('#userForm')[0].reset();
            }
            $('#msg_div').removeClass('d-none');
            setTimeout(function(){
            $('#res_message').hide();
            $('#msg_div').hide();
            },10000);
        }
      });
    }
  })
}


$(function() {
    $('.multiselect-ui').multiselect({
        includeSelectAllOption: true
    });
});

</script>

@endpush