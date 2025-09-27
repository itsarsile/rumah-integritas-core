<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Settings')]
class Settings extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar; // temporary uploaded file

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->update([
            'name' => $this->name,
        ]);

        $this->dispatch('profile-updated');
        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required'],
            'new_password' => ['required', PasswordRule::defaults(), 'confirmed'],
        ], [], [
            'current_password' => 'password saat ini',
            'new_password' => 'password baru',
        ]);

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        // reset fields
        $this->current_password = $this->new_password = $this->new_password_confirmation = '';
        session()->flash('success', 'Password berhasil diperbarui.');
    }

    public function updateAvatar()
    {
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $this->avatar->store('avatars', 'public');

        // remove old avatar file if was local path
        $user = Auth::user();
        $old = $user->avatar;
        $user->update(['avatar' => $path]);

        if ($old && !str_starts_with($old, 'http')) {
            @unlink(public_path('storage/' . $old));
        }

        $this->avatar = null;
        $this->dispatch('avatar-updated');
        session()->flash('success', 'Avatar berhasil diperbarui.');
    }

    public function removeAvatar()
    {
        $user = Auth::user();
        $old = $user->avatar;
        $user->update(['avatar' => null]);
        if ($old && !str_starts_with($old, 'http')) {
            @unlink(public_path('storage/' . $old));
        }
        $this->dispatch('avatar-updated');
        session()->flash('success', 'Avatar dihapus.');
    }

    public function render()
    {
        return view('livewire.account.settings');
    }
}
