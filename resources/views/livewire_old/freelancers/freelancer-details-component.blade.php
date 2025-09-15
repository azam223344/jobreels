<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Freelancers</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Detail
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row" id="basic-table">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$user->first_name}} {{$user->last_name}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{url('/')}}/uploads/{{$user->profile_picture}}" />
                                </div>
                                <div class="col-md-8">
                                    <h2>Basic Information</h2>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label>verification level</label>
                                            <span class="badge badge-{{$user->freelancer->verification_level == 'Verified' ? 'primary' : 'success'}}">{{$user->freelancer->verification_level}}</span>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>Email: </label>
                                            <p>{{$user->email}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>Phone: </label>
                                            <p>{{$user->phone}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>State: </label>
                                            <p>{{$user->state}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>City: </label>
                                            <p>{{$user->city}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>Address: </label>
                                            <p>{{$user->address}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>portfolio website</label>
                                            <p>{{$user->freelancer->portfolio_website ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>description</label>
                                            <p>{{$user->freelancer->description ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>salary requirements</label>
                                            <p>{{$user->freelancer->salary_requirements ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>full/Part time</label>
                                            <p>{{$user->freelancer->full_time ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>hourly rate</label>
                                            <p>{{$user->freelancer->hourly_rate ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>skills experience</label>
                                            <p>{{$user->freelancer->skills_experience ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>skills assessment</label>
                                            <p>{{implode(', ', json_decode($user->freelancer->skills_assessment)) ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>upwork</label>
                                            <p>{{$user->freelancer->upwork ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>fiverr</label>
                                            <p>{{$user->freelancer->fiverr ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>linkedin</label>
                                            <p>{{$user->freelancer->linkedin ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>instagram</label>
                                            <p>{{$user->freelancer->instagram ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>facebook</label>
                                            <p>{{$user->freelancer->facebook ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>youtube</label>
                                            <p>{{$user->freelancer->youtube ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>tiktok</label>
                                            <p>{{$user->freelancer->tiktok ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>twitter</label>
                                            <p>{{$user->freelancer->twitter ?? 'N/A'}}</p>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>photo</label>
                                            <img src="{{url('/')}}/uploads/{{$user->freelancer->photo}}" />
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>photo of govt id</label>
                                            <img src="{{url('/')}}/uploads/{{$user->freelancer->photo_of_govt_id}}" />
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>bills</label>
                                            <img src="{{url('/')}}/uploads/{{$user->freelancer->bills}}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <h2>Stats</h2>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>Videos: </label>
                                            {{$user->postsCount()}}
                                        </div>
                                    </div>
                                    {{-- <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label>Adoption Requests: </label>
                                            0
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
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
                                        <option value="Verified" {{ $user->freelancer->verification_level == 'Verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="Pro Verified" {{ $user->freelancer->verification_level == 'Verified' ? '' : 'selected' }}>Pro Verified</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="nav-justified">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#uploaded-videos" role="tab" aria-controls="uploaded-videos" aria-selected="true">
                                        Uploaded Videos
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#adoption-in" role="tab" aria-controls="adoption-in" aria-selected="false">
                                        Adoption Requests Received
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#adoption-out" role="tab" aria-controls="adoption-out" aria-selected="false">
                                        Adoption Requested
                                    </a>
                                </li> --}}
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="uploaded-videos" role="tabpanel" aria-labelledby="home-tab-justified">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>title</th>
                                                <th>description</th>
                                                <th>skill</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->posts as $post)
                                            <tr>
                                                <td>{{$post->title}}</td>
                                                <td>{{$post->description}}</td>
                                                <td>{{$post->skills}}</td>
                                                <td><a target="_blank" href="{{url('/')}}/posts/view?post_id={{$post->id}}">View</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="tab-pane" id="saved-videos" role="tabpanel" aria-labelledby="profile-tab-justified">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>title</th>
                                                <th>description</th>
                                                <th>skill</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->savedVideos() as $post)
                                            <tr>
                                                <td>{{$post->title}}</td>
                                                <td>{{$post->description}}</td>
                                                <td>{{$post->skills}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="adoption-in" role="tabpanel" aria-labelledby="settings-tab-justified">
                                    <table class="table">
                                        <tr>
                                            <th>Shelter</th>
                                            <th>Post</th>
                                            <th>State</th>
                                            <th>Breed</th>
                                            <th>Request Date</th>
                                        </tr>
                                        @foreach ($adoptionIn as $item)
                                        <tr>
                                            <td><a href="{{url('/')}}/users/detail?user_id={{$item->shelter->id}}">{{$item->shelter->name}}</a></td>
                                            <td><a href="{{url('/')}}/posts/view?post_id=}}$item->post->id||">{{$item->post->name}}</a></td>
                                            <td>{{$item->post->state->name}}</td>
                                            <td>{{$item->post->breed->name}}</td>
                                            <td>{{$item->created_at->format('d-m-Y h:i:s a')}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="tab-pane" id="adoption-out" role="tabpanel" aria-labelledby="settings-tab-justified">
                                    <table class="table">
                                        <tr>
                                            <th>Shelter</th>
                                            <th>Post</th>
                                            <th>State</th>
                                            <th>Breed</th>
                                            <th>Request Date</th>
                                        </tr>
                                        @foreach ($adoptionOut as $item)
                                        <tr>
                                            <td><a href="{{url('/')}}/users/detail?user_id={{$item->shelter->id}}">{{$item->shelter->name}}</a></td>
                                            <td><a href="{{url('/')}}/posts/view?post_id=}}$item->post->id||">{{$item->post->name}}</a></td>
                                            <td>{{$item->post->state->name}}</td>
                                            <td>{{$item->post->breed->name}}</td>
                                            <td>{{$item->created_at->format('d-m-Y h:i:s a')}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
