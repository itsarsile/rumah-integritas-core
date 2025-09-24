<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class LoginForm extends Component
{
    public $title;
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    #[Validate('required|exists:roles,name')]
    public $selectedRole = '';

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials)) {
            // Verify selected role matches user's assigned role(s)
            $user = Auth::user();

            $matchesSpatieRole = method_exists($user, 'hasRole') ? $user->hasRole($this->selectedRole) : false;
            $matchesUserColumn = isset($user->role) && $user->role === $this->selectedRole;

            if (!($matchesSpatieRole || $matchesUserColumn)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                session()->flash('error', 'Role tidak sesuai. Silakan pilih peran yang benar.');
                return null; // stay on login
            }

            // Optional: store active role in session
            session(['active_role' => $this->selectedRole]);

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

    #[Title('Login')]
    public function render()
    {
        $roles = Role::orderBy('name')->pluck('name');
        return view('livewire.login-form', [
            'roles' => $roles,
        ]);
    }
}
