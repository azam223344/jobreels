<?php

namespace App\Http\Livewire\Tables;

use App\Models\State;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class StatesTable extends LivewireDatatable
{
    public function builder()
    {
    return State::orderBy('name');
       // return State::query();
    }

    public function columns()
    {
        return [
         Column::name('name')->searchable(),
      //      Column::name('name')->label('Name'),
            BooleanColumn::name('active')->label('Active'),
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/states/edit?state_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>'.'<a href='.url('/').'/states/delete?state_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>';
             })->label('Action'),
        ];
    }
}
