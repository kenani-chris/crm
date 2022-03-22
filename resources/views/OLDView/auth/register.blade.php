@extends('layouts.app')

@section('pageParent', 'Users')

@section('pageTitle', 'Create New User')

@section('content')

        <div class="col-12">
            <div class="card">
                
                <div class="card-body">

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('User Role') }}</label>

                            <div class="col-md-6">

                                <select class="form-control @error('level') is-invalid @enderror" data-toggle="level" name="level" id="level" required>
                                             <option value="">Select user role</option>
                                                            <option value="Analyst">Agent</option>
                                                            <option value="WFC">Team Lead</option>
                                                            <option value="Admin">Admin</option>

                                                    </select>

                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="level" class="col-md-4 col-form-label text-md-right">Reporting Lead</label>

                            <div class="col-md-6">

                     

                                <select class="form-control @error('level') is-invalid @enderror" data-toggle="reporting_lead" name="reporting_lead" id="reporting_lead" required>

                                                           <option value="0">Select reporting lead</option>
                                                            @foreach($users as $user)
                                                            <option value={{$user->id}}>{{ucwords($user->name)}}</option>
                                                            @endforeach
                                                            <option value="any">Any</option>
                                                          
                                                     
                                                      
                                                     
                                                    
                                                    </select>

                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" value="ruhsolar@2021"  name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="ruhsolar@2021" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btm-block btn-secondary pull-right ">
                                    {{ __('Create new User') }} <i class="mdi mdi-account-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   
@endsection
