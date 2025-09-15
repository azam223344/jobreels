<?php

namespace App\Http\Livewire\Tables;

use App\Models\Breed;
use App\Models\Color;
use App\Models\Post;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class AdoptionRequestsTable extends LivewireDatatable
{
    public function builder()
    {
        return Transaction::query();
    }

    public function columns()
    {
        return [
            Column::callback(['shelter_user_id'], function($userId){
                $user = User::find($userId);
                return '<a target_blank href='.url('/').'/users/detail?user_id=' . $user->id . '>'.$user->name.'</a>';
            })->label('Shelter'),
            Column::callback(['adopter_user_id'], function($userId){
                $user = User::find($userId);
                return '<a target_blank href='.url('/').'/users/detail?user_id=' . $user->id . '>'.$user->name.'</a>';
            })->label('Adopter'),
            Column::name('postRel.name')->label('Post'),
            Column::callback(['post_id'], function($postId){
                $post = Post::find($postId);
                $state = State::find($post->state_id);
                return $state->name;
            })->label('State')->filterable(State::pluck('name')),
            Column::name('postRel.breed.name')->label('Breed')->filterable(Breed::pluck('name')),
            DateColumn::name('created_at')->label('Request Date')->filterable(),
            Column::name('amount')->label('Amount'),
        ];
    }
}
