<?php

namespace App\Http\Livewire\Colors;

use App\Models\Color;
use Livewire\Component;

class ColorsEditComponent extends Component
{
    public $color_id;
    public $color;

    protected $queryString = ['color_id'];

    public $rules = [
        'color.name' => 'required',
        'color.marks' => 'nullable',
        'color.active' => 'required|boolean'
    ];

    public function update()
    {
        $this->validate();

        $this->color->update();

        return redirect('/colors/list');
    }

    public function create()
    {
        $this->validate();

        $this->color->save();

        return redirect('/colors/list');
    }

    public function mount()
    {
        $this->color = Color::find($this->color_id);
        if(!$this->color) {
            $this->color = new Color();
            $this->color->active = true;
        }
    }

    public function render()
    {
        return view('livewire.colors.colors-edit-component');
    }
}
