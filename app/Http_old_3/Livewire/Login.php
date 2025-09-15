<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{

    public $username;
    public $password;
    public $credentialError = null;

    protected $rules = [
        'username' => 'required',
        'password' => 'required'
    ];

    public function login()
    {
        $this->validate();

        $result = Auth::attempt(['username' => $this->username, 'password' => $this->password]);

        if($result){
            return redirect('/');
        }
        else {
            $this->credentialError = "Invalid username or password";
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
