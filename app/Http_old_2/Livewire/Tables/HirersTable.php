<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class HirersTable extends LivewireDatatable
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
<a href=https://api.pms.mulum.pk/?user_id=' . $id . '></a>
';
            })->label('Action'),
            Column::callback(['first_name', 'last_name', 'profile_picture'], function ($first_name, $last_name, $picture) {
                $imageUrl = url('uploads/' . $picture);
                return "<div style='display: flex; flex-direction: row; gap: 1rem'>
                           <img src='{$imageUrl}' style='height: 60px; width: 60px; border-radius: 100%' alt='{$first_name} {$last_name}' />
                           {$first_name} {$last_name}
                        </div>";
            })->label('Name')->searchable(),
            Column::name('business_name')->label('Business')->searchable(),
            Column::name('email')->label('Email')->searchable(),
            Column::callback(['phone'], function ($phone) {
                if (is_numeric($phone) && !empty($phone)) {
                    return '<p>' . number_format($phone, 0, '', '-') . '</p>';
                }
                // Handle non-numeric or empty phone values gracefully, if needed.
            })->label('Phone')->searchable(),
            Column::name('state')->label('State')->searchable(),
            Column::name('city')->label('City')->searchable(),
        ];
    }
}
