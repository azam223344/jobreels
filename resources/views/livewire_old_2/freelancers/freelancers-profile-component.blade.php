
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Job Seekers</h5>
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
                                    <p class="card-text">{{$user->freelancer->verification_level == 'Verified' ? 'Unverified' : 'Verified'}}</p>
                                    <p class="card-text">{{ $user->email }}</p>
                                    <p class="card-text"> @if (!empty($user->phone) && is_numeric($user->phone))
                                        {{ number_format($user->phone, 0, '', '-') }}
                                    @else
                                        N/A <!-- or any other suitable message for empty/invalid phone numbers -->
                                    @endif</p>
                                    <p class="card-text">{{ $user->state }}</p>
                                </div>
                                
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Actions</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            <input wire:change="update" wire:model="user.active" type="checkbox" class="custom-control-input" checked id="customSwitch1">
                                            <label class="custom-control-label mr-1" for="customSwitch1">
                                            </label>
                                            <span>Account Active</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="custom-control custom-switch custom-control-inline mb-1">
                                            <input wire:change="update" wire:model="user.active_publisher" type="checkbox" class="custom-control-input" checked id="customSwitch2">
                                            <label class="custom-control-label mr-1" for="customSwitch2">
                                            </label>
                                            <span>Publishing Active</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="">Verification Level</label>
                                        <select wire:change="setVerificationLevel($event.target.value, {{$user->id}})" name="" id="" class="form-control">
                                            <option value="Verified" {{ $user->freelancer->verification_level == 'Verified' ? 'selected' : '' }}>Unverified</option>
                                            <option value="Pro Verified" {{ $user->freelancer->verification_level == 'Verified' ? '' : 'selected' }}>Verified</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <div class="card" style="height: 600px;">     
                   {{-- <div class="col-12 col-sm-12 col-md-12 col-lg-12"> --}}
                    <div class=" container row">
                        <div class="col-6  mt-3 col-lg-6>">
                            <b>Province:</b>{{$user->state}}
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
                            <b>Bio:</b>{{$user->description}}
                        </div>
                        @if($user->freelancer->verification_level == 'Verified')
                        <div class="col-md-6 mt-1">
                            <label>photo</label>
                            @if(empty($user->freelancer->photo))
                                N/A
                            @else
                                <a href="{{ url('/') }}/uploads/{{ $user->freelancer->photo }}" target="_blank">
                                    <img src="{{ url('/') }}/uploads/{{ $user->freelancer->photo }}" style="height:100px;"/>
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>photo of govt id</label>
                            @if(empty($user->freelancer->photo_of_govt_id))
                                N/A
                            @else
                                <a href="{{ url('/') }}/uploads/{{ $user->freelancer->photo_of_govt_id }}" target="_blank">
                                    <img src="{{ url('/') }}/uploads/{{ $user->freelancer->photo_of_govt_id }}" style="height:100px;"/>
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-1">
                            <label>bills</label>
                            @if(empty($user->freelancer->bills))
                                N/A
                            @else
                                <a href="{{ url('/') }}/uploads/{{ $user->freelancer->bills }}" target="_blank">
                                    <img src="{{ url('/') }}/uploads/{{ $user->freelancer->bills }}" style="height:100px;" />
                                </a>
                            @endif
                        </div>
                        
                        @endif
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
                                        <div style="max-height: 400px; overflow-y: scroll;">
                                            @foreach($myreports as $key => $report)
                                            @if($report->user)
                                            <div class="border-bottom py-1">
                                                @if ($report->user->type == 'freelancer')
                                                    <a href="{{url('/')}}/freelancers/detail?user_id={{$report->user->id}}"><h2 style="margin-bottom: 7px">{{$report->user->first_name}} {{$report->user->last_name}}</h2></a>
                                                @else
                                                    <a href="{{url('/')}}/hirers/detail?user_id={{$report->user->id}}"><h2 style="margin-bottom: 7px">{{$report->user->first_name}} {{$report->user->last_name}}</h2></a>
                                                @endif
            
                                                <div class="d-flex">
                                                    <div class="mr-1" style="width: 70px;">
                                                        @if ($report->user->type == 'freelancer')
                                                            <a href="{{url('/')}}/freelancers/detail?user_id={{$report->user->id}}">
                                                                <div class="avatar avatar-xl m-0">
                                                                    <img src="{{url('/')}}/uploads/{{$report->user->profile_picture}}" alt="avtar img holder" style="object-fit: cover;">
                                                                </div>
                                                            </a>
                                                        @else
                                                            <a href="{{url('/')}}/hirers/detail?user_id={{$report->user->id}}">
                                                                <div class="avatar avatar-xl m-0">
                                                                    <img src="{{url('/')}}/uploads/{{$report->user->profile_picture}}" alt="avtar img holder" style="object-fit: cover;">
                                                                </div>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="chip chip-danger mr-1">
                                                            <div class="chip-body">
                                                                <span class="chip-text">{{$report->flag->name}}</span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label>Bio:</label>
                                                            <p>{!! $report->description ? nl2br($report->description) : 'N/A' !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="myReports" role="tabpanel" aria-labelledby="myReports-tab">
                                        @foreach($otherreports as $key => $report)
                                        @if($report->getUserAttribute())
                                        <div class="border-bottom py-1">
                                            @if ($report->getUserAttribute()->type == 'freelancer')
                                                <a href="{{url('/')}}/freelancers/detail?user_id={{$report->getUserAttribute()->id}}"><h2 style="margin-bottom: 7px">{{$report->getUserAttribute()->first_name}} {{$report->getUserAttribute()->last_name}}</h2></a>
                                            @else
                                                <a href="{{url('/')}}/hirers/detail?user_id={{$report->getUserAttribute()->id}}"><h2 style="margin-bottom: 7px">{{$report->getUserAttribute()->first_name}} {{$report->getUserAttribute()->last_name}}</h2></a>
                                            @endif
        
                                            <div class="d-flex">
                                                <div class="mr-1" style="width: 70px;">
                                                    @if ($report->getUserAttribute()->type == 'freelancer')
                                                        <a href="{{url('/')}}/freelancers/detail?user_id={{$report->getUserAttribute()->id}}">
                                                            <div class="avatar avatar-xl m-0">
                                                                <img src="{{url('/')}}/uploads/{{$report->getUserAttribute()->profile_picture}}" alt="avtar img holder" style="object-fit: cover;">
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{url('/')}}/hirers/detail?user_id={{$report->getUserAttribute()->id}}">
                                                            <div class="avatar avatar-xl m-0">
                                                                <img src="{{url('/')}}/uploads/{{$report->getUserAttribute()->profile_picture}}" alt="avtar img holder" style="object-fit: cover;">
                                                            </div>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="chip chip-danger mr-1">
                                                        <div class="chip-body">
                                                            <span class="chip-text">{{$report->flag->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label>Bio</label>
                                                        <p>{!! $report->description ? nl2br($report->description) : 'N/A' !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                            <div class="card" style="max-height: 400px;">
                                <h5 class="card-header">Under Approval Posts</h5>
                                <div class="card-body" style="max-height: 100%; overflow-y: auto;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase">Post Date</th>
                                                <th class="text-uppercase text-center">Title</th>
                                                <th class="text-uppercase text-center">Description</th>
                                                <th class="text-uppercase text-center">Skill</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userposts as $usrpost)
                                            <tr>
                                                <td>{{ $usrpost->created_at->format('d M Y') }}</td>
                                                <td class="text-center">{{$usrpost->title}}</td>
                                                <td class="text-center">{{$usrpost->description}}</td>
                                                <td class="text-center">{{ is_array(json_decode($usrpost->skills)) ? implode(' , ', json_decode($usrpost->skills)) : ($usrpost->skills ?? 'N/A') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                            <div class="card" style="max-height: 400px;">
                                <!-- Check if the user has posts -->
                                @if ($user->posts && $user->posts->count() > 0)
                                    <div class="card-body" style="max-height: 100%; overflow-y: auto;">
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
                    <div class="card mt-3" style="max-height: 300px;">
                        <h5 class="card-header">Approval History</h5>
                        <div class="card-body" style="max-height: 100%; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Approval Type</th>
                                        <th class="text-uppercase">Date and Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->approvalHistory as $history)
                                        <tr>
                                            <td>{{ $history->type }}</td>
                                            <td>{{ $history->created_at->format('F j, Y g:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No approval history available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  
    
    
    