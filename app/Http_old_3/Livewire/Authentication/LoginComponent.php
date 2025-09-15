<?php

namespace App\Http\Livewire\Authentication;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email;
    public $password;

    public $rules = [
        'email' => 'required',
        'password' => 'required'
    ];

    public function login()
    {
        $this->resetErrorBag();

        $this->validate();

        if(Auth::attempt(['email' => $this->email, 'password' => $this->password]) || Auth::attempt(['username' => $this->email, 'password' => $this->password])){
            if(Auth::user()->type != 'admin'){
                $this->errorBag->add('logindetails', 'Invalid email or password provided');
                Auth::logout();
                return;
            }
            return redirect('/dashboard');
        }

        $this->errorBag->add('logindetails', 'Invalid email or password provided');

    }

    public function render()
    {
        return view('livewire.authentication.login-component')->layout('layouts.login');
    }
}
