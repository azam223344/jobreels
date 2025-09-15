<section id="auth-login" class="row flexbox-container" x-transition:enter="transition duration-200 transform ease-out">
    <div class="col-xl-8 col-11">
        <div class="mb-0 card bg-authentication">
            <div class="m-0 row">
                <!-- left section-login -->
                <div class="px-0 col-md-6 col-12">
                    <div class="p-2 mb-0 card disable-rounded-right h-100 d-flex justify-content-center">
                        <div class="pb-1 card-header">
                            <div class="card-title">
                                <h4 class="mb-2 text-center">Login</h4>

                            </div>
                        </div>
                        <div class="card-body">
                            @error('logindetails') <div class="alert alert-danger">{{ $message }}</div> @enderror
                            <div class="form-group mb-50">
                                <label class="text-bold-600" for="exampleInputEmail1">Email address</label>
                                <input type="email" wire:model='email' class="form-control" id="exampleInputEmail1"
                                    placeholder="Email address">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="text-bold-600" for="exampleInputPassword1">Password</label>
                                <input type="password" wire:model='password' class="form-control"
                                    id="exampleInputPassword1" placeholder="Password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button wire:click='login' class="btn btn-primary glow w-100 position-relative">Login<i
                                    id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                        </div>
                    </div>
                </div>
                <!-- right section image -->
                <div class="p-3 text-center col-md-6 d-md-block d-none align-self-center">
                    <img src="{{url('app-assets/images/logo/logo.png')}}" />
                </div>
            </div>
        </div>
    </div>
</section>
<small><?php echo password_hash('123456',PASSWORD_DEFAULT);?></small>
