<?php

namespace App\Http\Livewire\Tables;

use App\Models\Breed;
use App\Models\Color;
use App\Models\Post;
use App\Models\State;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PostsTable extends LivewireDatatable
{
    public function builder()
    {
        return Post::orderBy('is_featured', 'desc')->orderby('created_at','desc')->wherehas('user')->with(['user']);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function($id){
                return '<a href='.url('/').'/posts/view?post_id=' . $id . '><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a>
<a href=https://api.pms.mulum.pk/post.php?post_id=' . $id . '></a>
';
            }),
            Column::callback(['title', 'is_featured', 'is_approved_by_admin', 'active'], function($title, $is_featured, $is_approved_by_admin, $active) {
                $string = $title;

                if ($is_featured) $string .= '<br><span class="badge badge-success" style="margin-top: 3px">Featured</span>';

                if ($is_approved_by_admin) $string .= '<br><span class="badge badge-info" style="margin-top: 3px">Approved</span>';
                else $string .= '<br><span class="badge badge-warning" style="margin-top: 3px">Pending</span>';

                if (!$active) $string .= '<br><span class="badge badge-danger" style="margin-top: 3px">Inactive</span>';

                return $string;
            })->label('Name')->searchable(),
            Column::name('title')->label("Title")->searchable(),
            Column::callback(['skills'], function ($skills) {
                if (isset($skills)) {
                    $skillsArray = json_decode($skills);
            
                    if (is_array($skillsArray)) {
                        // Handle skills as an array within square brackets
                        $formattedSkills = implode(', ', $skillsArray);
                        return '<p>' . $formattedSkills . '</p>';
                    } else {
                        // Handle skills as a single string
                        return '<p>' . $skills . '</p>';
                    }
                } else {
                    // Handle cases where $skills is not set
                    return '<p>N/A</p>';
                }
            })->label('Skill')->searchable(), // Set a fixed width for the "Skill" column
            
            
             Column::callback(['user.id', 'user.first_name', 'user.last_name', 'user.active', 'user.active_publisher'], function($id, $first_name, $last_name, $active, $active_publisher) {
                $string = '<a href='.url('/').'/freelancers/detail?user_id=' . $id . '>'.$first_name.' '.$last_name.'</a>';

                if ($active_publisher) $string .= '<br><span class="badge badge-info" style="margin-top: 3px">Allowed</span>';
                else $string .= '<br><span class="badge badge-warning" style="margin-top: 3px">Not allowed</span>';

                if (!$active) $string .= '<br><span class="badge badge-danger" style="margin-top: 3px">Inactive</span>';

                return $string;
            })->label('Owner')->searchable(),
            Column::name('description')->label("Description")->searchable(),
            BooleanColumn::name('is_featured')->label('Feature')->filterable(),
            Column::name('status'),
            Column::name('status_description'),
             Column::name('created_at')->label('Post Time'),
        ];
    }
}
