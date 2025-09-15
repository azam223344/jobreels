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
    public $tablename;
    public $fromDate;
    public $toDate;
    public $selectedType;
    public $type;
    private $query;
    public $filteredData;
    
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
    public function search()
{
    // Build your query to filter data based on date range and user type
    if($this->selectedType == 'all_freelance' || $this->selectedType == 'approved_freelance' || $this->selectedType == 'pending_freelance'|| $this->selectedType == 'all_hires'|| $this->selectedType == 'subscribed_hires'|| $this->selectedType == 'unsubscribed_hires'){
    $this->query = User::query();
    if ($this->fromDate && $this->toDate) {
        $this->query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
    }

    if ($this->selectedType) {
        if ($this->selectedType == 'all_freelance') {
            $this->query->where('type', 'freelancer');
            $this->type="freelancers";
        } elseif ($this->selectedType == 'approved_freelance') {
            $this->query->where('type', 'freelancer')->where('active_publisher',1);
            $this->type="freelancers";
        } elseif ($this->selectedType == 'pending_freelance') {
            $this->query->where('type', 'freelancer')->where('active_publisher',0);
            $this->type="freelancers";
        }elseif ($this->selectedType == 'all_hires') {
            $this->query->where('type', 'hirer');
            $this->type="hirers";
        }elseif ($this->selectedType == 'subscribed_hires') {
            $this->query->where('type', 'hirer');
            $this->type="hirers";
        }elseif ($this->selectedType == 'unsubscribed_hires') {
            $this->query->where('type', 'hirer');
            $this->type="hirers";
        }
        // Add more conditions for other user types if needed
    }
    }elseif($this->selectedType == 'all_posts' || $this->selectedType == 'remote_posts' || $this->selectedType=='admin_posts' || $this->selectedType=='priority_posts'){ 
        $this->query=Post::wherehas('user');
        if ($this->fromDate && $this->toDate) {
            $this->query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        }
        $this->type="posts";
        if ($this->selectedType) {
            if ($this->selectedType == 'all_posts') {
                $this->query;
            } elseif ($this->selectedType == 'admin_posts') {
                $this->query->wherehas('user',function($q){
                    $q->where('type','admin');
                            });
            } elseif ($this->selectedType == 'remote_posts') {
                $this->query->wherehas('user',function($q){
                    $q->where('type','freelancer');
                            });
            }elseif ($this->selectedType == 'priority_posts') {
                $this->query;
            }
            // Add more conditions for other user types if needed
        }
    }
    // Fetch the filtered data
    if($this->query!=null){
    $this->filteredData = $this->query->get();
    }else{
        $this->filteredData = null; 
    }
    // Pass the filtered data to your view for rendering
    return view('livewire.dashboard.dashboard-component', ['filteredData' => $this->filteredData,'type' => $this->type,'fromDate'=>$this->fromDate,'toDate'=>$this->toDate]);
}

}
