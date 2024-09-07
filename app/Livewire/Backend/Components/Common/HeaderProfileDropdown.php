<?php

namespace App\Livewire\Backend\Components\Common;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HeaderProfileDropdown extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.backend.components.common.header-profile-dropdown');
    }
}
