<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile(){

        return view('profile.profile');
    }

    public function change_password(){

        return view('profile.password_change');
    }


    public function newPassword(Request $request){


        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required','min:6'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if(User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)])){

           // Auth::logoutOtherDevices('password');

            return redirect()->back()->with('message', 'Password changed successfully');


        }


    }

}
