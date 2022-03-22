@extends('layouts.app')

@section('pageParent', 'Home ')

@section('pageTitle','Unauthorized Access')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              

                <div class="card-body">

<div class="alert alert-danger">

                OOPs! Unauthorized access. Please contact the administrator


                </div>
                    @if (session('error'))
                    <div class="alert alert-danger">
                      {{ session('error') }}
                    </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection