<?php

namespace App\Http\Livewire\Breeds;

use App\Models\Breed;
use Livewire\Component;

class BreedsEditComponent extends Component
{
    public $breed_id;
    public $breed;

    protected $queryString = ['breed_id'];

    public $rules = [
        'breed.name' => 'required',
        'breed.active' => 'required|boolean'
    ];

    public function update()
    {
        $this->validate();

        $this->breed->name = ltrim($this->breed->name);

        $this->breed->update();

        return redirect('/breeds/list');
    }

    public function create()
    {
        $this->validate();
        $this->breed->name = ltrim($this->breed->name);
        $this->breed->save();

        return redirect('/breeds/list');
    }

    public function mount()
    {
        $this->breed = Breed::find($this->breed_id);
        if(!$this->breed) {
            $this->breed = new Breed();
            $this->breed->active = true;
        }
    }

    public function render()
    {
        return view('livewire.breeds.breeds-edit-component');
    }
}
