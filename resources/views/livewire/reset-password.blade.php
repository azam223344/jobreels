<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Reset Password</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Password
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="container card ">
                <div class="card-header">
                <h1>Reset Password</h1>
                </div>
                <div class="card-body table-responsive">
                    <div>
                        @if (session()->has('error') && session('error_field') === 'currentPassword')
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                        <form wire:submit.prevent="resetPassword">
                            <div>
                                <label for="currentPassword" class="form-label">Current Password:</label>
                                <input type="password" wire:model="currentPassword" id="currentPassword" class="form-control">
                                @error('currentPassword') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="newPassword" class="form-label">New Password:</label>
                                <input type="password" wire:model="newPassword" id="newPassword" class="form-control">
                                @error('newPassword') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="passwordConfirmation" class="form-label">Confirm Password:</label>
                                <input type="password" wire:model="passwordConfirmation" id="passwordConfirmation" class="form-control">
                                @error('passwordConfirmation') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary mt-2" style="float:right;">Reset Password</button>
                            </div>
                        </form>
                    </div>
                    
            </div>
            </div>
        </div>
    </div>
</div>


