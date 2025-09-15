<form method="post" wire:submit.prevent="login">
    @if($credentialError)
    <div class=" mb-2">
        <div class="alert alert-danger">{{$credentialError}}</div>
    </div>
    @endif

    <div class="form-group mb-50">
        <label class="text-bold-600" for="exampleInputEmail1">Username</label>
        <input type="text" name="username" class="form-control" wire:model="username" id="exampleInputEmail1" placeholder="Username"></div>
        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
    <div class="form-group">
        <label class="text-bold-600" for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" wire:model="password" id="exampleInputPassword1" placeholder="Password">
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
        <div class="text-left">
            <div class="checkbox checkbox-sm">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="checkboxsmall" for="exampleCheck1"><small>Keep me logged
                        in</small></label>
            </div>
        </div>
        <div class="text-right"><a href="auth-forgot-password.html" class="card-link"><small>Forgot Password?</small></a></div>
    </div>
    <button type="submit" wire:target="login" wire:loading.attr="disabled" class="btn btn-primary glow w-100 position-relative">Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
</form>
