<?php

namespace App\Livewire;

use App\Models\LoginSlide;
use Livewire\Component;

class LoginSlider extends Component
{
    public function render()
    {
        $slides = LoginSlide::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get()
            ->values();

        return view('livewire.login-slider', [
            'slides' => $slides,
        ]);
    }
}
