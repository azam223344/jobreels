<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mt-1 mb-2 content-header-left col-12">
                <div class="breadcrumbs-top">
                    <h5 class="float-left pr-1 mb-0 content-header-title">States</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="p-0 pl-1 mb-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active">States
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
                            <h4 class="card-title">States</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input class="form-control" wire:model.defer='state.name' />
                                </div>
                                <div class="col-md-12 mt-1">
                                    <fieldset>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" wire:model.defer='state.active' checked="" name="customCheck" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Active</label>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12 text-right">
                                    @if($state->id)
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
