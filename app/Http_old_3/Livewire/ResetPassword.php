<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithValidation;
class ResetPassword extends Component
{
    public $currentPassword;
    public $newPassword;
    public $passwordConfirmation;
    public $successMessage;
    public function render()
    {
        return view('livewire.reset-password');
    }
    public function resetPassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|different:currentPassword',
            'passwordConfirmation' => 'required|same:newPassword',
        ]);
    
        $user = Auth::user();
    
        if (Hash::check($this->currentPassword, $user->password)) {
            if (Hash::check($this->newPassword, $user->password)) {
                // Log the issue if new password matches the current password
                \Log::error('New password is the same as the current password.');
                session()->flash('error', 'New password cannot be the same as the current password.');
            } else {
                $user->update([
                    'password' => Hash::make($this->newPassword),
                ]);
                session()->now('success', 'Your password has been reset.'); // Store success message for the current request
                return redirect('/reset-password');  // Redirect to the specified URL
            }
        } else {
            session()->flash('error', 'Current password is incorrect.');
        session()->flash('error_field', 'currentPassword');
        }
    }
    
}
