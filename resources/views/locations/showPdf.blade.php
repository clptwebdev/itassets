@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', $location->name)

@section('content')
<section>
<table class="table  table-bordered p-1 mb-4" width="100%">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">Location Information</th>
        </tr>
    </thead>
    <tr>
        <td rowspan="6" width="15%">
            @if($location->photo()->exists())
                <img src="{{ asset($location->photo->path)}}" width="100%" alt="{{$location->name}}">
            @else
                <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$location->name}}">
            @endif
        </td>
        <td>{{ $location->name }}</td>
    </tr>
    <tr><td>{{ $location->address_1 }}</td></tr>
    <tr><td>{{ $location->address_2 }}</td></tr>
    <tr><td>{{ $location->city }}</td></tr>
    <tr><td>{{ $location->county }}</td></tr>
    <tr><td>{{ $location->postcode }}</td></tr>
</table>
@if($location->users()->exists())
<table class="table  table-bordered p-1" width="100%">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th>Authorised Users</th>
        </tr>
    </thead>
    <?php 
        $admin = \App\Models\User::where('role_id', 1)->pluck('id');
        $ids = $admin->merge($location->users->pluck('id'));
        $users = \App\Models\User::findMany($ids);
        ?>
    @foreach($users as $user)
    <tr><td>{{$user->name}}</td></tr>
    @endforeach
</table>
@endif
</section>
@if($location->asset()->exists())
<div class="page-break"></div>
<section>
    <table width="100%" class="table table-striped table-bordered">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Assets</th>
            </tr>
        <tr>
            <th width="20%;">Item</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Manufacturer</th>
            <th width="10%;">Date</th>
            <th width="10%;">Cost</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($location->asset as $asset)
            <tr>
                <td>{{ $asset->model->name ?? 'No Model'}}<br><small class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                <td align="center">
                    {!! '<div id="barcode"><img width="120px" height="30px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   /></div>' !!}
                    <span style="font-weight: 800">{{ $asset->asset_tag }}</span></td>
                <td class="text-center ">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                <td class="text-center">
                    
                    @if($asset->model)
                    <br>
                    @php
                    $eol = Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                    if($eol->isPast()){
                        $dep = 0;
                    }else{
                        $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                        $percent = 100 / $asset->model->depreciation->years;
                        $percentage = floor($age)*$percent;
                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                    }
                    @endphp
                    £{{ number_format($dep, 2)}}
                    <small>*£{{ $asset->purchased_cost }} (Original)</small>
                    @else
                    £{{ $asset->purchased_cost }}  
                    @endif                    
                </td>
                @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                <td class="text-center" data-sort="{{ $warranty_end }}">
                    {{ $asset->warranty }} Months
                    @php
                        $remaining = round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end));
                    @endphp
                    <br><small style="color:@if($remaining <= 0 ){{'#dc3545'}}@elseif($remaining < 6){{ '#ffc107'}}@else{{'#28a745'}}@endif;">{{ $remaining }} Remaining</small>
                </td>
                <td class="text-center" data-sort="{{ strtotime($asset->audit_date)}}">
                    @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                        <span style="color: #dc3545">{{\Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit Overdue</small></span>
                    @else
                        <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                        @switch(true)
                            @case($age < 31) <span style="color: #ffc107">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit Due Soon</small></span>
                                @break
                            @default
                                <span style="color: #666">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit due in {{floor($age)}} days</small></span>
                        @endswitch
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Item</small></th>
                <th><small>Tag</small></th>
                <th><small>Manufacturer</small></th>
                <th><small>Date</small></th>
                <th ><small>Cost</small></th>
                <th ><small>Warranty (M)</small></th>
                <th class="text-center"><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
</section>
@endif
@if($location->accessory()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Accessories</th>
            </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Manufacturers</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($location->accessory as $accessory)

            <tr>
                <td>{{$accessory->name}}
                    <br>
                    <small>{{$accessory->serial_no}}</small>
                </td>
                <td class="text-center">{{$accessory->manufacturer->name ?? "N/A"}}</td>
                <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$accessory->purchased_cost}}</td>
                <td>{{$accessory->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$accessory->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                <td class="text-center" data-sort="{{ $warranty_end }}">
                    {{ $accessory->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</section>
@endif
@if($location->component()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="7">Components</th>
        </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Manufacturers</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($location->component as $component)

            <tr>
                <td>{{$component->name}}
                    <br>
                    <small>{{$component->serial_no}}</small>
                </td>
                <td class="text-center">{{$component->manufacturer->name ?? "N/A"}}</td>
                <td>{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$component->purchased_cost}}</td>
                <td>{{$component->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$component->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                    {{ $component->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</section>
@endif
@if($location->consumable()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Consumables</th>
            </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Manufacturers</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($location->consumable as $consumable)

            <tr>
                <td>{{$consumable->name}}
                    <br>
                    <small>{{$consumable->serial_no}}</small>
                </td>
                <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$consumable->purchased_cost}}</td>
                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$consumable->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                <td class="text-center" data-sort="{{ $warranty_end }}">
                    {{ $consumable->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</section>
@endif
@endsection