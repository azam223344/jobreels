@if($loggedIn)
<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
        href="javascript:void(0);" data-toggle="dropdown">
        <div class="user-nav d-sm-flex d-none"><span class="user-name">{{$user->name}}</span></div><span>
            @if(Auth::user() && Auth::user()->getProfilePictureUrlAttribute())
    <img class="round" src="{{ Auth::user()->getProfilePictureUrlAttribute() }}" alt="avatar" height="40" width="40">
@else
    <img class="round" src="{{url('app-assets/images/logo/logo.png')}}" alt="dummy avatar" height="40" width="40">
@endif</span>
    </a>
    <div class="pb-0 dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{url('/logout')}}"><i
                class="bx bx-power-off mr-50"></i> Logout</a>
    </div>
</li>
@endif
