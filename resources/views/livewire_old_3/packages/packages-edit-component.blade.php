<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">Packages</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Packages
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Packages</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <label>Order</label>
                                    <input class="form-control" wire:model.defer='package.order' />
                                    @error('order') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>Name</label>
                                    <input class="form-control" wire:model.defer='package.name' />
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>tagline</label>
                                    <input class="form-control" wire:model.defer='package.tagline' />
                                    @error('tagline') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>paddle id monthly</label>
                                    <input class="form-control" wire:model.defer='package.paddle_id_monthly' />
                                    @error('paddle_id_monthly') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>amount monthly</label>
                                    <input class="form-control" wire:model.defer='package.amount_monthly' />
                                    @error('amount_monthly') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>paddle id annually</label>
                                    <input class="form-control" wire:model.defer='package.paddle_id_annually' />
                                    @error('paddle_id_annually') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>amount annually</label>
                                    <input class="form-control" wire:model.defer='package.amount_annually' />
                                    @error('amount_annually') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>details</label>
                                    <textarea class="form-control" wire:model.defer='package.details'></textarea>
                                    @error('details') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mt-1">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" wire:model.defer='package.active' checked="" name="customCheck" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Active</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 text-right">
                                    @if($package->id)
                                        <button class="btn btn-primary" wire:click='update' wire:target='update' wire:loading.attr='update'>Update</button>
                                    @else
                                        <button class="btn btn-primary" wire:click='create' wire:target='create' wire:loading.attr='create'>Create</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
