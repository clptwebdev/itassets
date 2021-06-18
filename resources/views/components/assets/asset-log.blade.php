@props(["asset"])
<div class="col-12 col-lg-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid #666;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold">Asset Log</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col mr-2">
                    <div class="mb-1">
                        <p class="mb-4">Log information regarding {{ $asset->model->name}} <strong
                                class="font-weight-bold btn btn-sm btn-secondary shadow-sm p-1"><small>#{{ $asset->asset_tag }}</small></strong>
                            , view history and activity regarding the selected asset.</p>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>