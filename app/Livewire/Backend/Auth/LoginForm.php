<?php

namespace App\Livewire\Backend\Auth;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LoginForm extends Component
{
    use LivewireAlert;
    public $email;
    public $password;
    public $remember;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            return to_route('dashboard');
        } else {
            $this->alert('error', 'Invalid Credentials');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.backend.auth.login-form');
    }
}
