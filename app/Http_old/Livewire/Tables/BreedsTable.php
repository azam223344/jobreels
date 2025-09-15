<?php

namespace App\Http\Livewire\Tables;

use App\Models\Breed;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BreedsTable extends LivewireDatatable
{
    public function builder()
    {
        return Breed::orderBy('name');
    }

    public function columns()
    {
        return [
            Column::name('name')->searchable(),
            BooleanColumn::name('active')->filterable(),
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/breeds/edit?breed_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>'.'<a href='.url('/').'/breeds/delete?breed_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>';
            })->label('Action')
        ];
    }
}
