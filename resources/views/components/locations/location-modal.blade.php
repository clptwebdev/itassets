@props(["asset"])
<div class="col-12 col-lg-4">
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
                            class="font-weight-bold btn btn-sm shadow-sm p-1 text-light" style="background-color: {{ $asset->location->icon}};">{{ $asset->location->name }}</strong>
                        , the location that is currently assigned to the asset and any request information.</p>

                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td class="text-center">
                                        @if(isset($asset->location->photo->path))
                                            <img src="{{ asset($asset->location->photo->path) ?? asset('images/svg/device-image.svg')}}" width="200px"
                                            alt="{{$asset->model->name}}">
                                        @else
                                            <img src="{{asset('images/svg/location-image.svg')}}" width="100px" alt="{{$asset->model->name}}">
                                        @endif
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td style="color: {{$asset->location->icon}};"><strong>{{ $asset->location->name }}</strong></td></tr>
                                <tr><td>{{ $asset->location->address_1 }}</strong></td></tr>
                                @if($asset->location->address_2 != "")
                                <tr><td>{{ $asset->location->address_2 }}</strong></td></tr>
                                @endif
                                <tr><td>{{ $asset->location->city }}</td></tr>
                                <tr><td>{{ $asset->location->county }}</td></tr>
                                <tr><td>{{ $asset->location->postcode }}</td></tr>
                            </tbody>
                        </table>

                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr><th colspan="2">Contact Details</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Telephone:</td><td>{{ $asset->location->telephone }}</td></tr>
                                <tr><td>Email:</td><td>{{ $asset->location->email }}</td></tr>
                            </tbody>
                        </table>

                        <button class="btn btn-sm btn-primary"><i class="far fa-envelope"></i> Email Location</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


