<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Hash;
use App\Models\User;
use Auth;

class Login extends Component
{

    public $users, $email, $password;

    public function render()
    {
        return view('livewire.login');
    }

    private function resetLoginFields(){
        $this->email='';
        $this->password='';
    }

    public function login(){
        $validateData=$this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt(array('email' => $this->email,'password'=>$this->password))){
             session()->now('message',"You have been successfully login");
             return redirect()->to('/dashboard');
        }else{
            session()->now('error','Incorrecct email or password');
        }
    }
}
