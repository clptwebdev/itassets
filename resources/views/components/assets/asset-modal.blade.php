@props(["asset"])
<div class="col-12 col-sm-8 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Asset Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12"><p class="mb-4 ">Information regarding <strong
                        class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1"><small>#{{ $asset->asset_tag }}</small></strong>
                    , the asset that is currently being Viewed and any request information attached.</p>
                <hr>
                </div>
                <div class="col-3 p-2">
                    @if(isset($asset->model->photo->path))
                    <img src="{{ asset($asset->model->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @else
                    <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @endif
                    <hr>
                    <img class="mb-4" src="{{ asset('images/barcode.gif')}}" width="100%" alt="Asset Tag {{$asset->asset_tag }}">
                    <p class="text-center font-weight-bold mx-4">Asset Tag: #{{ $asset->asset_tag }}</p>
                </div>
                <div class="col-9 p-2">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2">Device Information</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>Device Name:</td>
                            <td>{{ $asset->model->name }}</td>
                        </tr>
                        <tr>
                            <td>Device Model N<span class="">o</span></td>
                            <td>{{ $asset->model->model_no }}</td>
                        </tr>
                        <tr>
                            <td>Device Serial N<span class="">o</span></td>
                            <td>{{ $asset->serial_no }}</td>
                        </tr>
                        @foreach($asset->fields as $field)
                        <tr>
                            <td>{{ $field->name ?? 'Unknown' }}</td>
                            <td>{{ $field->pivot->value }}</td>
                        </tr>
                        @endforeach
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status: </td>
                            <td><strong>Booked</strong></td>
                            <td class="text-right"><button class="btn btn-sm btn-primary p-1 font-weight-bold">Change Status</button></td>
                        </tr>
                        <tr>
                            <td>Audit Date: </td>
                            <td><strong>{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</strong></td>
                            <td class="text-right">
                                @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                                    <button class="btn btn-sm btn-danger p-1 font-weight-bold">{{ 'Audit over due' }}</button>
                                @else
                                    <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                                    @switch(true)
                                        @case($age == 0)
                                            <button class="btn btn-sm btn-danger p-1 font-weight-bold">{{ 'Audit over due' }}</button>
                                            @break
                                        @case($age < 31)
                                            <button class="btn btn-sm btn-warning p-1 font-weight-bold">{{ 'Audit Due Soon' }}</button>
                                            @break
                                        @case($age >= 32) 
                                            <button class="btn btn-sm btn-success p-1 font-weight-bold">{{ round($age).' Days till Due' }}</button>
                                                @break
                                        @default
                                            <button class="btn btn-sm btn-danger p-1 font-weight-bold">{{ 'Unknown Audit Date'}}</button>
                                    @endswitch
                                @endif
                            </td>
                        </tr>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="3">Created by:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $asset->user->name ?? 'Unkown'}}</td>
                                <td>{{ $asset->created_at}}</td>
                                <td class="text-right">
                                    @if(isset($asset->user->id))
                                    <a href="{{ route('users.show', $asset->user->id)}}">
                                    <button class="font-weight-bold btn btn-sm btn-primary p-1">View User</button></a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm table-bordered">
                        <tr>
                            <td><strong
                                class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>Students</small></strong><strong
                                    class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1 m-2"><small>Ipads</small></strong><strong
                                        class="font-weight-bold d-inline-block btn-sm btn-dark shadow-sm p-1 m-2"><small>Tablets</small></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



