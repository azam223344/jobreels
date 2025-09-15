@if($loggedIn)
<li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
        href="javascript:void(0);" data-toggle="dropdown">
        <div class="user-nav d-sm-flex d-none"><span class="user-name">{{$user->name}}</span></div><span>
            <img class="round" src="https://jobreels.ph/jobreels-laravel/public/Logo.svg" alt="avatar" height="40"
                width="40"></span>
    </a>
    <div class="pb-0 dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{url('/')}}"><i
                class="bx bx-power-off mr-50"></i> Logout</a>
    </div>
</li>
@endif
