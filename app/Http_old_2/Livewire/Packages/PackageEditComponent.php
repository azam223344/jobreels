<?php

namespace App\Http\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;

class PackageEditComponent extends Component
{
    public $package_id;
    public $package;

    protected $queryString = ['package_id'];

    public $rules = [
        'package.order' => 'required|integer|min:1',
        'package.name' => 'required|string|max:190',
        'package.tagline' => 'nullable|string|max:190',
        'package.paddle_id_monthly' => 'nullable|string',
        'package.amount_monthly' => 'nullable|numeric|gt:0',
        //'package.discounted_amount_monthly' => 'nullable|numeric',
        'package.paddle_id_annually' => 'nullable|string',
        'package.amount_annually' => 'nullable|numeric|gt:0',
        //'package.discounted_amount_annually' => 'nullable|numeric',
        //'package.highlighted_details' => 'nullable|string',
        'package.details' => 'nullable|string',
        'package.active' => 'required|boolean'
    ];

    public function update()
    {
        \Log::info($this->package);
        \Log::info($this->validate());

        $this->package->update();

        return redirect('/packages/list');
    }

    public function create()
    {
        $this->validate();

        $this->package->save();

        return redirect('/packages/list');
    }

    public function mount()
    {
        $this->package = Package::find($this->package_id);
        if(!$this->package) {
            $this->package = new Package();
            $this->package->active = true;
        }
    }

    public function render()
    {
        return view('livewire.packages.packages-edit-component');
    }
}
