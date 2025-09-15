<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class HirersSubscribedTable extends LivewireDatatable
{
    public function builder()
    {
        return User::orderby('users.created_at','desc')->where('type', 'hirer');
        //return User::query();
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/hirers/detail?user_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>
<a href=https://api.pms.mulum.pk/?user_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>
';
            })->label('Action'),
            Column::callback(['first_name', 'last_name'], function($first_name, $last_name) {
                return '<div style="display: flex;flex-direction: row;gap: 1rem">
                   <img src="{{url(/)}}/uploads/{{$user->profile_picture}}" style="height: 60px;width: 60px;border-radius: 100%" />'.
                    $first_name.' '.$last_name.'
                </div>'
                    ;
            })->label('Name')->searchable(),
            Column::name('business_name')->label('Business')->searchable(),
            Column::name('email')->label('Email')->searchable(),
            Column::name('phone')->label('Phone')->searchable(),
            Column::name('state')->label('State')->searchable(),
            Column::name('city')->label('City')->searchable(),
        ];
    }
}
