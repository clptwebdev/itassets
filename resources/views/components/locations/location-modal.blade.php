@props(["asset"])

<div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$asset->location->icon ?? '#666'}};">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold" style="color: {{$asset->location->icon ?? '#666'}};">Location
            Information</h6>
    </div>
    <div class="card-body">
        <div class="row no-gutters">
            <div class="col mr-2">
                <div class="mb-1">
                    <p class="mb-4">Information regarding <strong
                            class="font-weight-bold btn btn-sm shadow-sm p-1 text-light" style="background-color: {{$asset->location->icon ?? '#666'}};">{{ $asset->location->name }}</strong>
                        , the location that is currently assigned to the asset and any request information.</p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            @if($asset->location->photo()->exists())
                                <img src="{{ asset($asset->location->photo->path) ?? asset('images/svg/device-image.svg')}}" width="200px"
                                alt="{{$asset->location->photo->name}}">
                            @else
                                <img src="{{asset('images/svg/location-image.svg')}}" width="100px" alt="{{$asset->name}}">
                            @endif
                        </div>
                        <div class="col-12 col-lg-8">
                            <table class="table table-sm table-bordered table-striped">
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
                            @if($asset->location->email && $asset->location->email != "")
                            <a href="mailto:{{ $asset->location->email}}"><button class="btn btn-sm btn-primary"><i class="far fa-envelope"></i> Email Location</button></a>
                            @endif
                        </div>
                    </div>
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>


