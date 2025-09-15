<?php

namespace App\Http\Livewire\Tables;

use App\Models\State;
use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class UsersTable extends LivewireDatatable
{
    public function builder()
    {
        return User::query();
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/users/detail?user_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>';
            })->label('Action'),
            Column::name('name')->label('Name')->searchable(),
            Column::name('username')->label('UserName')->searchable(),
            Column::name('email')->label('Email')->searchable(),
            Column::name('phone')->label('Phone')->searchable(),
            Column::name('address')->label('Address')->searchable(),
            Column::name('state.name')->label('State')->filterable(State::pluck('name'))->searchable(),
            BooleanColumn::name('active')->label('Active')
        ];
    }
}
