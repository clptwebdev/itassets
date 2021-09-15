@extends('layouts.pdf-reports')

@section('title', 'Asset  Report')

@section('page', $asset->name)

@section('user', $user->name)

@section('content')
        <div style="width: 62%; pading-right: 3%; float: left;">
            <table class="table table-sm table-bordered table-striped" width="100%">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Device Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Device Name:</td>
                    <td>{{ $asset->name }}</td>
                </tr>
                @if($asset->model()->exists())
                <tr>
                    <td>Device Model N<span class="">o</span></td>
                    <td>{{$asset->model->name}}<br><small>{{ $asset->model->model_no }}</small></td>
                </tr>
                @endif
                <tr>
                    <td>Device Serial N<span class="">o</span></td>
                    <td>{{ $asset->serial_no }}</td>
                </tr>
                @if($asset->fields()->exists())
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
                @endif

            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th colspan="3">Status </th>
                </tr>
                </thead>
                <tr>
                    <td>Device Status: </td>
                    <td><strong>{{ $asset->status->name}}</strong></td>
                    <td class="text-right"></td>
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
                                    <span class="text-danger">{{ 'Audit over due' }}</span>
                                    @break
                                @case($age < 31)
                                    <span class="text-warning p-1 ">{{ 'Audit Due Soon' }}</span>
                                    @break
                                @case($age >= 32) 
                                    <span class="text-success p-1">{{ round($age).' Days till Due' }}</span>
                                        @break
                                @default
                                    <span class="text-danger p-1">{{ 'Unknown Audit Date'}}</span>
                            @endswitch
                        @endif
                    </td>
                </tr>
                @if($asset->model()->exists())
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
                                    <span class="text-danger">Sorry for your loss</span>
                                    @break
                                @case($age < 31) 
                                    <span class="text-warning">End is Near</span>
                                    @break
                                @case($age >= 32)
                                    <span class="text-success">Life in the Old Dog</span>
                                    @break
                                @default
                                    <span class="text-danger">Unknown</span>
                                @endswitch
                        @endif
                    </td>
                </tr>
                @endif
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Created by:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $asset->user->name ?? 'Unkown'}}</td>
                        <td>{{ $asset->created_at}}</td>
                    </tr>
                </tbody>
            </table>
            @if($asset->category()->exits())
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
        <div style="width: 32%; padding-left: 3%;float: right; border-left: solid 3px #CCC;">
            @if($asset->model->photo()->exists())
                <img src="{{ asset($asset->model->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
            @else
                <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
            @endif
            <hr>
            {!! '<img width="100%" height="100px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   />' !!}
            <p class="text-center font-weight-bold mx-4">Asset Tag: #{{ $asset->asset_tag }}</p>
            <hr>
            <?php $manufacturer = $asset->model->manufacturer; ?>
            <div class="text-center">
            @if($manufacturer->photo->exists())
            <img src="{{ asset($manufacturer->photo->path)}}"
                width="70%" alt="{{$manufacturer->name}}">
            @endif
            </div>
            <p><strong>{{ $manufacturer->name }}</strong></p>
            <p>Tel: {{ $manufacturer->supportPhone }}</p>
            <p>Email: {{ $manufacturer->supportEmail }}</p>
            <p>URL: {{ $manufacturer->supportUrl ?? 'Unknown'}}</p>
        </div>
    </div>
    <div class="page-break"></div>
    <section class="d-block" style="margin-top: 50px; clear: both;">
    <div class="w-100 d-block">
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Purchase Information Information</th>
            </tr>
            <tr>
                <td class="col-4">Order N<sup>o</sup>:</td>
                <td class="col-8"> {{$asset->order_no }}</td>
            </tr>
            <tr>
                <td>Supplier:</td>
                <td>
                    @if($asset->supplier()->exists())
                    {{ $asset->supplier->name}}<br>
                    {{ $asset->supplier->address_1 }}<br>
                    {{ $asset->supplier->address_2 }}<br>
                    {{ $asset->supplier->city }}<br>
                    {{ $asset->supplier->county }}<br>
                    {{ $asset->supplier->postcode }}<br>
                    @else
                    {{'No Supplier Information Available'}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Date of Purchase:</td>
                <td>
                    <?php $purchase_date = \Carbon\Carbon::parse($asset->purchased_date);?>
                    {{ $purchase_date->format('d/m/Y')}}
                </td>
            </tr>
            <tr>
                <td>Warranty</td>
                <td>
                    <?php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);?>
                    {{ $asset->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                </td>
            </tr>
            @if($asset->purchased_cost > 0)
            <tr>
                <td>Purchase Cost:</td>
                <?php $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date); $percentage = floor($age)*33.333; $dep = $asset->purchased_cost * ((100 - $percentage) / 100);?>
                <td>£{{ $asset->purchased_cost }} - (Current Value*: £{{ number_format($dep, 2)}})<br>
                <small>*Calculated using Depreciation Model:</small><br><strong
                    class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1"><small>Laptop and Tablet</small></strong></p></td>
            </tr>
            @endif
        </table>
    </div>
    @if($asset->location()->exists())
    <hr>
    <div class="w-100 d-block">
        
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><td>Location Information</td></tr>
            @if($asset->location->photo()->exists())
            <tr>
                <td class="text-center p-1">
                    <div style="width: 150px; overflow:hidden; margin-left: auto; margin-right: auto;">
                        <img src="{{ asset($asset->location->photo->path)}}" width="100%" title="{{ $asset->location->name}}">
                    </div>
                </td>
            </tr>
            @endif
            <tr><td style="color: {{$asset->location->icon}};"><strong>{{ $asset->location->name }}</strong></td></tr>
            <tr><td>{{ $asset->location->address_1 }}</strong></td></tr>
            @if($asset->location->address_2 != "")
            <tr><td>{{ $asset->location->address_2 }}</strong></td></tr>
            @endif
            <tr><td>{{ $asset->location->city }}</td></tr>
            <tr><td>{{ $asset->location->county }}</td></tr>
            <tr><td>{{ $asset->location->postcode }}</td></tr>
        </table>

    </div>
    @endif
</section>

@if($asset->comment()->exists())
<div class="page-break"></div>
<p>Comments</p>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        
        @foreach($asset->comment as $comment)
        <tr>
            <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($asset->logs()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        @foreach($asset->logs()->orderBy('created_at', 'desc')->take(30)->get() as $log)
        <tr>
            <td class="text-left">{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection