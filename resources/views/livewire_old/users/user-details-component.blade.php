<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Users</h5>
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
                            <h4 class="card-title">{{$user->name}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{url('/')}}/uploads/{{$user->profile_picture}}" />
                                </div>
                                <div class="col-md-8">
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <h2>Basic Information</h2>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>Email: </label>
                                            {{$user->email}}
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phone: </label>
                                            {{$user->phone}}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>State: </label>
                                            {{$user->state->name ?? ''}}
                                        </div>
                                        <div class="col-md-6">
                                            <label>Address: </label>
                                            {{$user->address}}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <h2>Stats</h2>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>Videos: </label>
                                            {{$user->postsCount()}}
                                        </div>
                                        <div class="col-md-6">
                                            <label>Likes: </label>
                                            {{$user->likesCount()}}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label>Saved Videos: </label>
                                            {{$user->savedCount()}}
                                        </div>
                                        <div class="col-md-6">
                                            <label>Adoption Requests: </label>
                                            0
                                        </div>
                                    </div>
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
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#saved-videos" role="tab" aria-controls="saved-videos" aria-selected="true">
                                        Saved Videos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="messages-tab-justified" data-toggle="tab" href="#liked-videos" role="tab" aria-controls="liked-videos" aria-selected="false">
                                        Liked Videos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#commented-videos" role="tab" aria-controls="commented-videos" aria-selected="false">
                                        Commented Videos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#adoption-in" role="tab" aria-controls="adoption-in" aria-selected="false">
                                        Adoption Requests Received
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#adoption-out" role="tab" aria-controls="adoption-out" aria-selected="false">
                                        Adoption Requested
                                    </a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="uploaded-videos" role="tabpanel" aria-labelledby="home-tab-justified">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                                <th>
                                                    Breed
                                                </th>
                                                <th>
                                                    Color
                                                </th>
                                                <th>
                                                    State
                                                </th>
                                                <th>
                                                    Shipping Avl.
                                                </th>
                                                <th>
                                                    Price
                                                </th>
                                                <th>

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->posts as $post)
                                            <tr>
                                                <td>
                                                    {{$post->name}}
                                                </td>
                                                <td>
                                                    {{$post->description}}
                                                </td>
                                                <td>
                                                    {{$post->breed->name ?? ''}}
                                                </td>
                                                <td>
                                                    {{$post->color->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{$post->state->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{$post->shipping_available ? 'Yes' : 'No'}}
                                                </td>
                                                <td>
                                                    ${{$post->price}}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{url('/')}}/posts/view?post_id={{$post->id}}">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="saved-videos" role="tabpanel" aria-labelledby="profile-tab-justified">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                                <th>
                                                    Breed
                                                </th>
                                                <th>
                                                    Color
                                                </th>
                                                <th>
                                                    State
                                                </th>
                                                <th>
                                                    Shipping Avl.
                                                </th>
                                                <th>
                                                    Price
                                                </th>
                                                <th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->savedVideos() as $savedPost)
                                            <tr>
                                                <td>
                                                    {{$savedPost->name}}
                                                </td>
                                                <td>
                                                    {{$savedPost->description}}
                                                </td>
                                                <td>
                                                    {{$savedPost->breed->name ?? ''}}
                                                </td>
                                                <td>
                                                    {{$savedPost->color->name ?? ''}}
                                                </td>
                                                <td>
                                                    {{$savedPost->state->name ?? ''}}
                                                </td>
                                                <td>
                                                    {{$savedPost->shipping_available ? 'Yes' : 'No'}}
                                                </td>
                                                <td>
                                                    ${{$savedPost->price}}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{url('/')}}/posts/view?post_id={{$savedPost->id}}">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="tab-pane" id="liked-videos" role="tabpanel" aria-labelledby="messages-tab-justified">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                                <th>
                                                    Breed
                                                </th>
                                                <th>
                                                    Color
                                                </th>
                                                <th>
                                                    State
                                                </th>
                                                <th>
                                                    Shipping Avl.
                                                </th>
                                                <th>
                                                    Price
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->likedVideos() as $likedPost)
                                            <tr>
                                                <td>
                                                    {{$likedPost->name}}
                                                </td>
                                                <td>
                                                    {{$likedPost->description}}
                                                </td>
                                                <td>
                                                    {{$likedPost->breed->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{$likedPost->color->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{$likedPost->state->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{$likedPost->shipping_available ? 'Yes' : 'No'}}
                                                </td>
                                                <td>
                                                    ${{$likedPost->price}}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{url('/')}}/posts/view?post_id={{$likedPost->id}}">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="commented-videos" role="tabpanel" aria-labelledby="settings-tab-justified">
                                    <p>
                                        Biscuit powder jelly beans. Lollipop candy canes croissant icing chocolate cake. Cake fruitcake powder
                                        pudding pastry.I love caramels caramels halvah chocolate bar. Cotton candy
                                        gummi bears pudding pie apple pie cookie.
                                    </p>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
