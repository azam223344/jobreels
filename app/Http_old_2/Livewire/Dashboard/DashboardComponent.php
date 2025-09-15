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
        $this->totalPosts = Post::count();
        $this->totalReports = FlagedContent::where('resolved', 0)->count();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-component');
    }
}
