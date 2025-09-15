<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row">
                    <div class="col-xl-12 col-12 dashboard-users">
                        <div class="row ">
                            <!-- Statistics Cards Starts -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-3 col-12 dashboard-users-danger">
                                        <a href="{{url('/')}}/freelancers/list">
                                            <div class="text-center card">
                                                <div class="py-1 card-body">
                                                    <div class="mx-auto badge-circle badge-circle-lg badge-circle-light-danger mb-50">
                                                        <i class="bx bxs-info-circle font-medium-5"></i>
                                                    </div>
                                                    <div class="text-muted line-ellipsis">All Freelancers</div>
                                                    <h3 class="mb-0">{{$totalFreelancers}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 col-12 dashboard-users-danger">
                                        <a href="{{url('/')}}/freelancers/pending-approval">
                                            <div class="text-center card">
                                                <div class="py-1 card-body">
                                                    <div class="mx-auto badge-circle badge-circle-lg badge-circle-light-danger mb-50">
                                                        <i class="bx bxs-info-circle font-medium-5"></i>
                                                    </div>
                                                    <div class="text-muted line-ellipsis">Pending Freelancers</div>
                                                    <h3 class="mb-0">{{$totalPendingFreelancers}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 col-12 dashboard-users-danger">
                                        <a href="{{url('/')}}/hirers/list">
                                            <div class="text-center card">
                                                <div class="py-1 card-body">
                                                    <div class="mx-auto badge-circle badge-circle-lg badge-circle-light-danger mb-50">
                                                        <i class="bx bxs-info-circle font-medium-5"></i>
                                                    </div>
                                                    <div class="text-muted line-ellipsis">Hirers</div>
                                                    <h3 class="mb-0">{{$totalHirers}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 col-12 dashboard-users-danger">
                                        <a href="{{url('/')}}/posts/list">
                                            <div class="text-center card">
                                                <div class="py-1 card-body">
                                                    <div class="mx-auto badge-circle badge-circle-lg badge-circle-light-danger mb-50">
                                                        <i class="bx bxs-info-circle font-medium-5"></i>
                                                    </div>
                                                    <div class="text-muted line-ellipsis">Posts</div>
                                                    <h3 class="mb-0">{{$totalPosts}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3 col-12 dashboard-users-danger">
                                        <a href="{{url('/')}}/posts/flagged">

										<div class="text-center card">
                                                <div class="py-1 card-body">
                                                    <div class="mx-auto badge-circle badge-circle-lg badge-circle-light-danger mb-50">
                                                        <i class="bx bxs-info-circle font-medium-5"></i>
                                                    </div>
                                                    <div class="text-muted line-ellipsis">Open Reports</div>
                                                    <h3 class="mb-0">{{$totalReports}}</h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Revenue Growth Chart Starts -->
                        </div>
                    </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fromDate">From Date</label>
                                <input wire:model="fromDate" type="date" class="form-control" id="fromDate">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="toDate">To Date</label>
                                <input wire:model="toDate" type="date" class="form-control" id="toDate">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select wire:model="selectedType" class="form-control" id="type">
                                    <option Selected>Select Type</option>
                                    <option value="all_freelance">All Job Seekers</option>
                                    <option value="approved_freelance">Approved Job Seekers</option>
                                    <option value="pending_freelance">Pending Jobs Seekers</option>
                                    <option value="all_hires">All hires</option>
                                    <option value="subscribed_hires">Subscribed Hires</option>
                                    <option value="unsubscribed_hires">Unsubscribed Hires</option>
                                    <option value="all_posts">All Posts</option>
                                    <option value="remote_posts">Remote Worker Posts</option>
                                    <option value="admin_posts">Admin Posts</option>
                                    <option value="priority_posts">Priority Posts</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <button wire:click="search" class="btn btn-primary">Search</button>
                        </div>
                    
                      
                </div>
            </section>
            @if ($filteredData != null)
            @if ($filteredData->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Search</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                @if($type=='posts')
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Title</th>
                            <th>Skills</th>
                            <!-- Add more table headers as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filteredData as $item)
                        <tr>
                            <td><a href="{{url('/').'/'.$type.'/view?post_id='. $item->id}}"><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a></td>
                            <td>{{ $item->title }}</td>
                            <td>{{ is_array(json_decode($item->skills)) ? implode(' , ', json_decode($item->skills)) : ($item->skills ?? 'N/A') }}</td>
                            <!-- Add more table cells as needed -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <!-- Add more table headers as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filteredData as $item)
                                <tr>
                                    <td><a href="{{url('/').'/'.$type.'/detail?user_id='. $item->id}}"><i class="badge-circle badge-circle-light-secondary bx bx-edit font-medium-1"></i></a></td>
                                    <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <!-- Add more table cells as needed -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @else
            <p>No data found.</p>
        @endif
        
        </div>
    </div>
</div>
