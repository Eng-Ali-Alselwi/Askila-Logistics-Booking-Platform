<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ToggleUserActivation extends Component
{
    public $user;
    public bool $is_active;

    public function mount(int $userId)
    {
        $this->user = User::findOrFail($userId);
        // $this->user = $user;
        $this->is_active = $this->user->is_active;
        // dd($this->is_active);
    }

    public function updatedIsActive($value)
    {
        dd('mujahid');
        $this->toggleActivation();
    }


    public function toggleActivation()
    {
        dd("mujahid");
        //  dd('mujahid');
        // dd('hellow');
        // Check if the logged-in user is a super admin
        if (auth()->user()->isSuperAdmin()) {

            dd('mujahid');
            // Allow super admins to toggle other users' activation status
            // $this->user->update(['is_active' => $this->is_active]);

        }
    }

    public function render()
    {
        return view('livewire.toggle-user-activation');
    }
}
