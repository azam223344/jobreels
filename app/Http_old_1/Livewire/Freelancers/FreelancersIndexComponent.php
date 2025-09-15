<?php

namespace App\Http\Livewire\Freelancers;

use App\Models\User;
use Livewire\Component;

class FreelancersIndexComponent extends Component
{
    public $userToDeleteId ;
    public $showDeleteConfirmation; 
    public function confirmDelete($userId)
    {
        $this->userToDeleteId = $userId;
        $this->showDeleteConfirmation = true;
    }
    public function delete()
{
    $user = User::find($this->userToDeleteId);
    if ($user) {
        $user->delete();
        // Optionally, you can emit an event to refresh the datatable
        $this->emit('refreshDatatable');
        $this->showDeleteConfirmation = false; // Close the modal after deletion
    }
}

    public function render()
    {
        return view('livewire.freelancers.freelancers-index-component');
    }

}
