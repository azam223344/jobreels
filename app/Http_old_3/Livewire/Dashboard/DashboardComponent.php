<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Breed;
use App\Models\Color;
use App\Models\FlagedContent;
use App\Models\Post;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $totalFreelancers;
    public $totalPendingFreelancers;
    public $totalHirers;
    public $totalPosts;
    public $totalReports;

    public function mount()
    {
        $this->totalFreelancers = User::where('type', 'freelancer')->count();
        $this->totalPendingFreelancers = User::where('type', 'freelancer')->where('active_publisher', 0)->count();
        $this->totalHirers = User::where('type', 'hirer')->count();
        $this->totalPosts = Post::wherehas('user')->count();
        $this->totalReports = Post::whereRaw(\DB::raw("((select count(id) from flaged_contents where flaged_contents.post_id = posts.id ) != 0)"))->select('posts.*', \DB::raw('(select count(id) from flaged_contents where flaged_contents.post_id = posts.id and resolved = 0) as flag_count'))->wherehas('user')->orderby('created_at')->count();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-component');
    }
}
