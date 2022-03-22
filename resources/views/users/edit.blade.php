@extends('layouts.app')

@section('pageParent', 'Users')

@section('pageTitle', 'Update '.$user->name.' Details')

@section('content')
<div class="row">
        <div class="col-6">
            <div class="card ">
            <h4 class="pl-3 pb-2 pt-2" >Edit user</h4>
            <div style="border-bottom:1px solid #ccc"></div>
           
                <div class="card-body">

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                    <form method="POST"  id="userForm" action="javascript:void(0)">
                   
                    <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label for="name" class="col-md-12col-form-label ">{{ __('Name') }}</label>

                           
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-12col-form-label ">{{ __('E-Mail') }}</label>

                         
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="col-md-12col-form-label ">{{ __('Phone') }}</label>

                        
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ $user->phone_number }}" required>

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="pf_no" class=" col-form-label ">{{ __('PF No') }}</label>

                        
                                <input id="pf_no" type="text" class="form-control @error('pf_no') is-invalid @enderror" name="pf_no" value="{{ $user->pf_no }}">

                                @error('pf_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="role_id" class="col-md-12col-form-label ">{{ __('User Role') }}</label>

                        

                                <select class="form-control @error('role_id') is-invalid @enderror" data-toggle="role_id" name="role_id" id="role_id" required>
                                <option value="">Select role</option>
                                @foreach($user->roles as $myrole)
                                <option value="{{$myrole->id}}" {{$user->role->id==$myrole->id ? 'selected' : '' }}>{{$myrole->name}}</option>
                                      @endforeach  
                                </select>

                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="branch_id" class="col-md-12col-form-label ">{{ __('Branch') }}</label>

                        

                                <select class="form-control @error('branch_id') is-invalid @enderror" data-toggle="branch_id" name="branch_id" id="branch_id" required>
                                <option value="">Select branch</option>
                                @foreach($user->branches as $branch)
                                        <option value="{{$branch->id}}" {{$user->branch_id==$branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                      @endforeach  
                                </select>

                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="brand" class="col-md-12col-form-label ">{{ __('Brands') }}</label>

                        

                            <?php
                                    $arr1=array($user->brands);
                                    $arr2=array($user->allbrands);

                                    
                            ?>

                        
                                <select style="height" class="multiselect-ui form-control @error('brand') is-invalid @enderror" multiple="multiple" name="brand[]" id="brand" required >
                              
                                @foreach($user->brands as $brand)
                                <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                                @endforeach
                                      @foreach($user->allbrands as $allbrand)
                                         <option value="{{$allbrand->id}}" >{{$allbrand->name}}</option>
                                      @endforeach
                                </select>



                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>



                     
                        <div class="form-group">
                            <label for="campaign_id" class="col-form-label ">{{ __('Department') }}</label>

                        

           
                                <select style="height" class="form-control @error('campaign_id') is-invalid @enderror" name="campaign_id" id="campaign_id" required >
                                <option value="">Select</option>
                                @foreach($user->campaigns as $campaign)
                                <option value="{{$campaign->id}}" {{$user->campaign_id==$campaign->id ? 'selected' : ''}}>{{$campaign->name}}</option>
                               
                                @endforeach
                                </select>


                                @error('campaign_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="alert alert-success d-none" id="msg_div">
                                                    <span id="res_message"></span>
                                                    </div>
                        <div class="form-group mb-0">
                           
                              
                                <button type="submit" class="btn btn-secondary pull-right waves-effect waves-light createUser">Edit User <i class="mdi mdi-account-edit mr-1"></i> </button>

                           
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
            <h4 class="pl-3 pb-2 pt-2" >Reset Password</h4>
            <div style="border-bottom:1px solid #ccc"></div>
                <div class="card-body">
                <form method="POST"  id="resetPasswordForm" action="javascript:void(0)">
                <div class="alert alert-success d-none" id="pass_div">
                                                    <span id="pass_message"></span>
                                                    </div>
                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                       

                        <div class="form-group ">
                            <label for="password" class="col-form-label ">New Password</label>

                          
                                <input id="password" type="password" class="form-control" name="password" autocomplete="password">
                          
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" >
                        </div>


                        <div class="form-group mb-0">
                           
                              
                                <button type="submit" class="btn btn-secondary pull-right waves-effect waves-light resetPassword">Reset Password <i class="mdi mdi-lock mr-1"></i> </button>

                           
                        </div>

                </form>


</div>
</div>
</div>
</div>
@endsection

@push('scripts_2')

@endpush

@push('scripts')


<script type="text/javascript">


if ($("#resetPasswordForm").length > 0) {
    $("#resetPasswordForm").validate({

    
    rules: {
        password: {
        required: true,
        minlength: 6,
      },
      password_confirmation:{
          required:true
      },
    
     
    },
    messages: {
       
        new_password: {
            required: "New password is required",
            minlength: "New password should be more than 3 characters."
        },
        new_confirm_password: {
            required: "New confirm password is required"
        }
        
    },
    submitHandler: function(form) {

      

    
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('.resetPassword').html('Resetting Passwod..');
      $.ajax({
        url: "{{route('password.reset',request()->id)}}" ,
        type: "PUT",
        data: $('#resetPasswordForm').serialize(),
        success: function( response ) {
            $('.resetPassword').html('Update User');
            $('#pass_message').show();
            $('#pass_message').html(response.msg);
            $('#pass_div').removeClass('d-none');
            setTimeout(function(){
            $('#pass_message').hide();
            $('#pass_div').hide();
            },10000);
        }
      });
    }
  })
}


if ($("#userForm").length > 0) {
    $("#userForm").validate({

     
     
    rules: {
      name: {
        required: true,
        minlength: 3
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
      email: {
            required: true,
            maxlength: 100,
            email:true
        },

     
    },
    messages: {
       
        name: {
            required: "Name is required",
            maxlength: "Name should be 50 characters long.",
            minlength: "Name should be more than 3 characters."
        },
        role_id: {
            required: "User role is required"
        },
        branch_id:{
            required: "Branch is required"
        },
        brand:{
            required: "Brand is required"
        },
        email: {
            required: "Please enter valid email",
            email: "Please enter valid email",
            maxlength: "The email should less than or equal to 100 characters"
        }
        
    },
    submitHandler: function(form) {

      

    
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('.createUser').html('Updating user..');
      $.ajax({
        url: "{{route('update.user',request()->id)}}" ,
        type: "PUT",
        data: $('#userForm').serialize(),
        success: function( response ) {
            $('.createUser').html('Update User');
            $('#res_message').show();
            $('#res_message').html(response.msg);
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