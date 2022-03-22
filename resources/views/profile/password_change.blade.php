@extends('layouts.app')

@section('pageParent', ucwords(Auth::user()->name))

@section('pageTitle',  ucwords(Auth::user()->name).' Change Password')

@section('content')

        <div class="col-12">
            <div class="card">
                
                <div class="card-body">

                <div class="col-md-8">

                <form method="POST" action="{{ route('new.password') }}">
                        @csrf 

                
                        <div class="col-md-12">
                         @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                         @endforeach 

                         @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }}
                                </div>
                         @endif
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                            <div class="col-md-8">
                                <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div class="col-md-8">
                                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-secondary">
                                    Update Password <i class="mdi mdi-lock-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>

             
                                
            
            </div>

               
                  
                </div>
            </div>
        </div>
   
@endsection