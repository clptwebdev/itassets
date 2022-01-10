@props(["asset"])
<?php $manufacturer = $asset->manufacturer; ?>

<div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid #666;">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Manufacturer
            Information</h6>
    </div>
    <div class="card-body">
        <div class="row no-gutters">
            <div class="col mr-2">
                <div class="mb-1">
                    <p class="mb-4">Information regarding <strong
                            class="font-weight-bold btn btn-sm btn-grey shadow-sm p-1">{{ $manufacturer->name ?? "No Manufacturer" }}</strong>
                        , the manufacturer that is currently assigned to the asset and any request information.</p>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <td class="text-center">
                                @if(isset($manufacturer->photo->path))
                                    <img
                                        src="{{ asset($manufacturer->photo->path ?? null) ?? asset('images/svg/device-image.svg')}}"
                                        width="250px" alt="{{$manufacturer->name ?? "No Manufacturer"}}">
                                @else
                                    <img src="{{asset('images/svg/manufacturer-image.svg')}}" width="250px"
                                         alt="{{$manufacturer->name ?? "No Manufacturer"}}">
                                @endif
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><strong>{{ $manufacturer->name ?? "No Manufacturer"}}</strong></td>
                        </tr>
                        <tr>
                            <td>Telephone:</td>
                        </tr>
                        <tr>
                            <td>{{ $manufacturer->supportPhone ?? 'No Telephone Number Available'}}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                        </tr>
                        <tr>
                            <td>{{ $manufacturer->supportEmail ?? 'No Email Address Available' }}</td>
                        </tr>
                        <tr>
                            <td>URL:</td>
                        </tr>
                        <tr>
                            <td>{{ $manufacturer->supportUrl ?? 'No Website Available' }}</td>
                        </tr>
                        </tbody>
                    </table>
                    @if($manufacturer->supportEmail && $manufacturer->supportEmail != "")
                        <a href="mailto:{{ $manufacturer->supportEmail ?? "No Manufacturer"}}">
                            <button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email Manufacturer
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
