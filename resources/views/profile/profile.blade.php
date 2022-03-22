@extends('layouts.app')

@section('pageParent', ucwords(Auth::user()->name))

@section('pageTitle',  ucwords(Auth::user()->name).' Profile')

@section('content')

        <div class="col-12">
            <div class="card">
                
                <div class="card-body">

                <div class="col-md-6">

                <table class="table table-striped">
                                <tbody>
                                <tr> <td class="font-weight-bold">Name</td> <td >{{ucwords(Auth::user()->name)}}</td></tr>

                                <tr> <td class="font-weight-bold">Email</td> <td >{{Auth::user()->email}}</td></tr>

                                <tr> <td class="font-weight-bold">Role</td> <td >{{Auth::user()->level=="Supervisor"? 'Team Lead': Auth::user()->level}}</td></tr>
                        
                                <tr> <td class="font-weight-bold">Member since</td> <td >{{Auth::user()->created_at}}</td></tr>
                               

                                </tbody>
                                </table>

                                <a href="{{route('password.change')}}">Change Password</a>

                                </div>

               
                  
                </div>
            </div>
        </div>
   
@endsection