<?php

namespace App\Http\Livewire\States;

use App\Models\State;
use Livewire\Component;

class StatesEditComponent extends Component
{
    public $state_id;
    public $state;

    protected $queryString = ['state_id'];

    public $rules = [
        'state.name' => 'required',
        'state.active' => 'required|boolean'
    ];

    public function update()
    {
        $this->validate();

        $this->state->update();

        return redirect('/states/list');
    }

    public function create()
    {
        $this->validate();

        $this->state->save();

        return redirect('/states/list');
    }

    public function mount()
    {
        $this->state = State::find($this->state_id);
        if(!$this->state) {
            $this->state = new State();
            $this->state->active = true;
        }
    }

    public function render()
    {
        return view('livewire.states.states-edit-component');
    }
}
