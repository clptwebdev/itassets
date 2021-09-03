@props(["asset"])
<div class="col-12 col-lg-8 mb-4">
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
                <div class="col-12 col-sm-5 col-md-3 p-2">
                    @if(isset($asset->model->photo->path))
                    <img src="{{ asset($asset->model->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @else
                    <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @endif
                    <hr>
                    {!! '<img width="100%" height="100px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   />' !!}
                    <p class="text-center font-weight-bold mx-4">Asset Tag: #{{ $asset->asset_tag }}</p>
                </div>
                <div class="col-12 col-sm-7 col-md-9 p-2">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="2">Device Information</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>Device Name:</td>
                            <td>{{ $asset->name }}</td>
                        </tr>
                        <tr>
                            <td>Device Model N<span class="">o</span></td>
                            <td>{{$asset->model->name}}<br><small>{{ $asset->model->model_no }}</small></td>
                        </tr>
                        <tr>
                            <td>Device Serial N<span class="">o</span></td>
                            <td>{{ $asset->serial_no }}</td>
                        </tr>
                        @foreach($asset->fields as $field)
                        <tr>
                            <td>{{ $field->name ?? 'Unknown' }}</td>
                            <td>
                                @if($field->type == 'Checkbox')
                                    @php($field_values = explode(',', $field->pivot->value))
                                    <ul>
                                    @foreach($field_values as $id=>$key)
                                        <li>{{ $key }}</li>
                                    @endforeach
                                    </ul>
                                @else
                                {{ $field->pivot->value }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status </th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status: </td>
                            <td><strong><i class="{{$asset->status->icon ?? 'fa fa-circle'}}" style="color: {{$asset->status->colour ?? '#666'}};"></i> {{ $asset->status->name ?? 'No Status Set'}}</strong></td>
                            <td class="text-right"><button class="btn btn-sm btn-primary p-1 font-weight-bold" data-toggle="modal" data-target="#assetModalStatus">Change Status</button></td>
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
                        <tr>
                            <td>End of Life (EOL): </td>
                            @php($eol =\Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->model->eol)->format('d/m/Y'))
                            <td><strong>{{ $eol }}</strong></td>
                            <td class="text-right">
                                @if(\Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->model->eol)->isPast())
                                <button class="btn btn-sm btn-danger p-1 font-weight-bold">{!! '<i class="fas fa-skull-crossbones"></i> Sorry for your loss' !!}</button>
                                @else
                                <?php $age = Carbon\Carbon::now()->floatDiffInDays(\Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->model->eol));?>
                                @switch(true)
                                @case($age == 0)
                                <button class="btn btn-sm btn-danger p-1 font-weight-bold">{!! '<i class="fas fa-skull-crossbones"></i> Sorry for your loss' !!}</button>
                                @break
                                @case($age < 31) <button class="btn btn-sm btn-warning p-1 font-weight-bold text-dark">{!! '<i class="fas fa-book-medical"></i> End is Near' !!}</button>
                                    @break
                                    @case($age >= 32)
                                    <button class="btn btn-sm btn-success p-1 font-weight-bold">{!! '<i class="fas fa-book"></i> Life in the Old Dog' !!}</button>
                                    @break
                                    @default
                                    <button class="btn btn-sm btn-danger p-1 font-weight-bold">{!! '<i class="fas fa-book-dead"></i> Unknown'!!}</button>
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
                    @if(count($asset->category) != 0)
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td>
                                @foreach($asset->category as $category)
                                <strong class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



