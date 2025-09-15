<?php

namespace App\Http\Livewire\Tables;

use App\Models\Freelancer;
use App\Models\State;
use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class FreelancersTable extends LivewireDatatable
{
    public function builder()
    {
        return User::orderby('users.created_at','desc')->where('type', 'freelancer')->where('active_publisher', 1);
        //return User::query();
    }
    public function userdelete($userId)
{
    $user = User::find($userId);
    if ($user) {
        $user->delete();
        Freelancer::where('user_id',$userId)->delete();
        // Optionally, you can emit an event to refresh the datatable
        $this->emit('refreshDatatable');
    }
}
    public function columns()
    {
        return [
            Column::callback(['id'], function($id) {
                return '<a href='.url('/').'/freelancers/detail?user_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>
                    <a wire:click="userdelete(' . $id . ')"><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>';
            })->label('Action'),
            
            Column::callback(['first_name', 'last_name', 'profile_picture'], function ($first_name, $last_name, $picture) {
                $imageUrl = url('uploads/' . $picture);
                return "<div style='display: flex; flex-direction: row; gap: 1rem'>
                           <img src='{$imageUrl}' style='height: 60px; width: 60px; border-radius: 100%' alt='{$first_name} {$last_name}' />
                           {$first_name} {$last_name}
                        </div>";
            })->label('Name')->searchable(),
            Column::callback(['freelancer.verification_level'], function($verification_level) {
                return '<span class="badge badge-' . ($verification_level == 'Verified' ? 'danger' : 'success') . '">' . ($verification_level == 'Verified' ? 'Unverified' : 'Verified') . '</span>';
            })->label('Level'),
            Column::name('email')->label('Email')->searchable(),
            Column::callback(['phone'], function ($phone) {
                if (is_numeric($phone) && !empty($phone)) {
                    return '<p>' . number_format($phone, 0, '', '-') . '</p>';
                }
                // Handle non-numeric or empty phone values gracefully, if needed.
            })->label('Phone')->searchable(),
            Column::name('state')->label('Province')->searchable(),
            Column::name('city')->label('City')->searchable(),
        ];
    }
}
