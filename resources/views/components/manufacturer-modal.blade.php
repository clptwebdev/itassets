@props(["asset"])
<div class="col-12 col-lg-6">
<div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$asset->manufacturer->photo}};">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold" style="color: {{$asset->manufacturer->photo}};">Manufacturers
            Information</h6>
    </div>
    <div class="card-body">
        <div class="row no-gutters">
            <div class="col mr-2">
                <div class="mb-1">
                    <p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-none d-sm-inline-block  btn-sm btn-primary shadow-sm padding-25-width">{{ $asset->manufacturer->name }}</strong>
                        , the manufacturer that is currently assigned to the asset and any request information.</p>
                    <p> Name:{{ $asset->manufacturer->name }}</p>
                    <p>Website:{{ $asset->manufacturer->supportUrl }}</p>
                    <p>Tel: {{ $asset->manufacturer->supportPhone }}</p>
                    <p>Email: {{ $asset->manufacturer->SupportEmail }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

