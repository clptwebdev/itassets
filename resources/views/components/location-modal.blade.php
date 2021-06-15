@props(["asset"])
<div class="col-12 col-lg-6">
<div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$asset->location->icon}};">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold" style="color: {{$asset->location->icon}};">Location
            Information</h6>
    </div>
    <div class="card-body">
        <div class="row no-gutters">
            <div class="col mr-2">
                <div class="mb-1">
                    <p class="mb-4">Information regarding <strong
                            class="font-weight-bold d-none d-sm-inline-block  btn-sm btn-primary shadow-sm padding-25-width">{{ $asset->location->name }}</strong>
                        , the location that is currently assigned to the asset and any request information.</p>
                    <p> {{ $asset->location->name }}</p>
                    <p>{{ $asset->location->address_1 }}</p>
                    @if($asset->location->address_2 != "")
                        <p>{{ $asset->location->address_2 }}</p>
                    @endif
                    <p> {{ $asset->location->city }}</p>
                    <p>{{ $asset->location->post_code }}</p>
                    <p>Tel: {{ $asset->location->telephone }}</p>
                    <p>Email: {{ $asset->location->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


