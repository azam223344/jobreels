<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Posts</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">View
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$post->title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-12 mt-1">
                                    <video controls="true" width="640" height="360">
                                        <source src="{{ $post->video }}" />
                                    </video>
                        </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12 mt-1">
                                    <label>description</label>
                                    <p>{!! $post->description ? nl2br($post->description) : 'N/A' !!}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>skill</label>
                                    <p>{{ $post->skills ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>portfolio</label>
                                    <p>{{ $post->portfolio ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>upwork</label>
                                    <p>{{ $post->upwork ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>fiverr</label>
                                    <p>{{ $post->fiverr ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>linkedin</label>
                                    <p>{{ $post->linkedin ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>instagram</label>
                                    <p>{{ $post->instagram ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>facebook</label>
                                    <p>{{ $post->facebook ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>youtube</label>
                                    <p>{{ $post->youtube ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>tiktok</label>
                                    <p>{{ $post->tiktok ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>twitter</label>
                                    <p>{{ $post->twitter ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>status</label>
                                    <p>{{ $post->status ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>status_description</label>
                                    <p>{{ $post->status_description ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="avatar mr-1 avatar-xl">
                                <img src="{{url('/')}}/uploads/{{$post->user->profile_picture}}" alt="avtar img holder">
                            </div>
                            <a href="{{url('/')}}/freelancers/detail?user_id={{$post->user->id}}">{{$post->user->first_name}} {{$post->user->last_name}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Action</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label for="">Action Taken</label>
                                    <select wire:model="actionTaken" name="" id="" class="form-control">
                                        <option value="">Select Action
                                        </option>
                                        @foreach($actions as $action)
                                        <option value="{{$action}}">{{$action}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="custom-control custom-switch custom-control-inline mb-1">
                                        <input wire:model="userStatus" type="checkbox" class="custom-control-input" checked id="customSwitch1">
                                        <label class="custom-control-label mr-1" for="customSwitch1">
                                        </label>
                                        <span>Account Active</span>
                                    </div>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="custom-control custom-switch custom-control-inline mb-1">
                                        <input wire:model="postStatus" type="checkbox" class="custom-control-input" checked id="customSwitch2">
                                        <label class="custom-control-label mr-1" for="customSwitch2">
                                        </label>
                                        <span>Post Active</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="">Action Description</label>
                                    <textarea class="form-control" wire:model="actionDescription"></textarea>
                                </div>
                                <div class="col-12 mt-1">
                                    <button wire:click="save" class="btn btn-md btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">Reports</h4>
                        </div>
                        <div class="card-body">
                            <div style="max-height: 400px; overflow-y: scroll;">
                                @foreach($reports as $key => $report)
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
                                                <label>Description</label>
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
    </div>
</div>
