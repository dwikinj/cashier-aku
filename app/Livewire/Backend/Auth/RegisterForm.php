<?php

namespace App\Livewire\Backend\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegisterForm extends Component
{
    use LivewireAlert;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function submit()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
            'password' => Hash::make($this->password),
            'role' => User::ROLE_CASHIER,
        ]);

        $this->alert('success', 'Succesfully Create Account');

        return to_route('login');
    }

    public function render()
    {
        return view('livewire.backend.auth.register-form');
    }
}
