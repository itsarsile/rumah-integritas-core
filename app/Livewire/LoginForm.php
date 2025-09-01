<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginForm extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials)) {
            session()->flash('message', 'Successfully logged in');
            return $this->redirect('/dashboard');
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('error', 'Email not found.');
        } else {
            session()->flash('error', 'Invalid password.');
        }
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
