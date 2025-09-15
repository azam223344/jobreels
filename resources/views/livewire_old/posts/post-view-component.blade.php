<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Posts</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$post->title}}</h4>
                        </div>
                        <div class="card-body">
                            <video controls="true" style="max-height: 350px;width: 88%">
                                <source src="{{$post->video}}" />
                            </video>
                            <div class="mt-1 col-12">
                                <div class="mb-1 custom-control custom-switch custom-control-inline">
                                    <input wire:model="post.active" wire:change='update' type="checkbox" class="custom-control-input" checked id="customSwitch2">
                                    <label class="mr-1 custom-control-label" for="customSwitch2">
                                    </label>
                                    <span>Post Active</span>
                                </div>
                                 <div class="mb-1 custom-control custom-switch custom-control-inline">
                                    <input wire:model="post.is_approved_by_admin" wire:change='postapproved' type="checkbox" class="custom-control-input" checked id="customSwitch3">
                                    <label class="mr-1 custom-control-label" for="customSwitch3">
                                    </label>
                                    <span>Post Approved</span>
                                </div>
                                <div class="mb-1 custom-control custom-switch custom-control-inline">
                                    <input wire:model="post.is_featured" wire:change='update' type="checkbox" class="custom-control-input" checked id="customSwitch1">
                                    <label class="mr-1 custom-control-label" for="customSwitch1">
                                    </label>
                                    <span>Post Featured</span>
                                </div>
                            </div>
                            <div class="mr-1 avatar avatar-xl mt-2">
                                <img src="{{url('/')}}/uploads/{{$post->user->profile_picture}}" alt="avtar img holder" style="object-fit: cover;">
                            </div>
                            <a href="{{url('/')}}/freelancers/detail?user_id={{$post->user->id}}">{{$post->user->first_name}} {{$post->user->last_name}}</a>

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

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
