<style>
    .smaller-image {
        width: 75px; /* Set your desired width */
        height: 75px; /* Set your desired height */
        overflow: hidden;
        margin: 0 auto 10px; /* Add some margin at the bottom */
    }
    
    .smaller-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .custom-bg-gray {
        background-color: #ccc; /* Replace with your desired gray color */
    }
    
    
        </style>
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Hirers</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">{{$user->first_name}} Profile
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="card" style="height: 350px;">
                                <div class="card-body text-center">
                                    <div class="image-wrapper">
                                        <div class="rounded-circle smaller-image">
                                            <img src="{{$user->getProfilePictureUrlAttribute()}}" alt="Card image cap">
                                        </div>
                                    </div>
                                    <h5 class="card-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                    <p class="card-text">{{ $user->email }}</p>
                                    <p class="card-text">{{ $user->phone }}</p>
                                    <p class="card-text">{{ $user->state }}</p>
                                </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <div class="card" style="height: 350px;">     
                   {{-- <div class="col-12 col-sm-12 col-md-12 col-lg-12"> --}}
                    <div class=" container row">
                        <div class="col-6  mt-3 col-lg-6>">
                            <b>State:</b>{{$user->state}}
                        </div>
                        <div class="col-6  mt-3 col-lg-4>">
                            <b>City</b>{{$user->city}}
                        </div>
                        <div class="col-6  mt-3 col-lg-6>">
                            <b>Address: </b>{{$user->address}}
                        </div>
                        <div class="col-6  mt-3 col-lg-6>">
                            <b>Zip code:</b>{{$user->zip_code}}
                        </div>
                        <div class="col-12  mt-3 col-lg-12>">
                            <b>Description:</b>{{$user->description}}
                        </div>
                    </div>
                   </div>
                        {{-- </div> --}}
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="reports-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="true">Reports</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="myReports-tab" data-toggle="tab" href="#myReports" role="tab" aria-controls="myReports" aria-selected="false">Reports on My Posts</a>
                                    </li>
                                </ul>
                    
                                <!-- Tab panes -->
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                                        <!-- Dummy data for "Reports" tab -->
                                        <ul>
                                            @if(isset($user->myreports))
                                            @foreach($user->myreports as $myreports)
                                            <li>{{$myreports->description}}</li>
                                            @endforeach
                                            @endif
                                            <!-- Add more report items here -->
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="myReports" role="tabpanel" aria-labelledby="myReports-tab">
                                        <!-- Dummy data for "Reports on My Posts" tab -->
                                        <ul>
                                            @if(isset($user->reportsonme))
                                            @foreach($user->reportsonme as $reportsonme)
                                            <li>{{$reportsonme->description}}</li>
                                            @endforeach
                                            @endif
                                            <!-- Add more report items here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="card">
                            <h5 class="card-header">Under Approval Posts</h5>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                <th class="text-uppercase"> Post Date </th>
                                <th class="text-uppercase text-center"> Title</th>
                                <th class="text-uppercase text-center"> Description </th>
                                <th class="text-uppercase text-center"> skill </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userposts as $usrpost)
                            <tr>
                                <td>{{ $usrpost->created_at->format('d M Y') }}</td>
                                <td class="text-center">{{$usrpost->title}}</td>
                                <td class="text-center">{{$usrpost->description}}</td>
                                <td class="text-center">{{ implode(' , ', json_decode($usrpost->skills)) ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                             
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="card">
                            <!-- Check if the user has posts -->
                            @if ($user->posts && $user->posts->count() > 0)
                                <div class="card-body">
                                    <h5 class="card-title">User Videos</h5>
                                    <div class="row">
                                        @foreach ($user->posts as $post)
                                            <div class="col-6 mb-4">
                                                <div class="video-container">
                                                    <!-- Display the video from the post -->
                                                    <video src="{{ $post->video }}" width="100%" controls></video>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  
    
    
    