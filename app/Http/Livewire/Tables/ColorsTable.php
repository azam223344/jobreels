<?php

namespace App\Http\Livewire\Tables;

use App\Models\Color;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ColorsTable extends LivewireDatatable
{
    public function builder()
    {
        return Color::orderBy('name');
    }

    public function columns()
    {
        return [
            Column::name('name')->label('Name'),
            BooleanColumn::name('marks')->label('Marks'),
            BooleanColumn::name('active')->label('Active'),
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/colors/edit?color_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>';
            })->label('Action')
        ];
    }
}
