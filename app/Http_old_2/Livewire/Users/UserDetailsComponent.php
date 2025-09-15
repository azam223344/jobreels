<?php

namespace App\Http\Livewire\Users;

use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class UserDetailsComponent extends Component
{
    public $user_id;
    public $adoptionIn;
    public $adoptionOut;
    public $user;

    public $queryString = ['user_id'];

    protected function rules(){
        return [
            'user.active_publisher' => 'required|boolean',
            'user.active' => 'required|boolean'
        ];
    }

    public function update()
    {
        $this->user->update();
    }

    public function mount()
    {
        $this->user = User::findOrFail($this->user_id);
        $this->adoptionIn = Transaction::where('shelter_user_id', $this->user->id)->get();
        $this->adoptionOut = Transaction::where('adopter_user_id', $this->user->id)->get();
    }

    public function render()
    {
        return view('livewire.users.user-details-component');
    }
}
