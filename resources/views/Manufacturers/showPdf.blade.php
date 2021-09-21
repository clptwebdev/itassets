@extends('layouts.pdf-reports')

@section('title', 'Manufacturer Report')

@section('page', $manufacturer->name)

@section('content')
<section>
<table class="table  table-bordered p-1 mb-4" width="100%">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">Manufacturer Information</th>
        </tr>
    </thead>
    <tr>
        <td rowspan="4" width="15%">
            @if($manufacturer->photo()->exists())
                <img src="{{ asset($manufacturer->photo->path)}}" width="100%" alt="{{$manufacturer->name}}">
            @else
            <span style="width: 100px; height: 100px; background-colour: #222;">No Image Available</span>
            @endif
        </td>
        <td>{{ $manufacturer->name }}</td>
    </tr>
    <tr><td>{{ $manufacturer->supportUrl ?? 'Unknown'}}</td></tr>
    <tr><td>{{ $manufacturer->supportPhone ?? 'Unknown'}}</td></tr>
    <tr><td>{{ $manufacturer->supportEmail ?? 'Unknown'}}</td></tr>
</table>

</section>
@if($manufacturer->assetModel()->exists())
<section>
    <table width="100%" class="table table-striped table-bordered">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="8">Assets</th>
            </tr>
        <tr>
            <th width="20%;">Item</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Location</th>
            <th width="10%;">Supplier</th>
            <th width="10%;">Date</th>
            <th width="10%;">Cost</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($manufacturer->assetModel as $model)
        @foreach($model->assets as $asset)
                <tr>
                    <td>{{ $asset->name ?? 'No Name'}}<br>{{ $asset->model->name ?? 'No Model'}}</td>
                    <td align="center">#{{ $asset->asset_tag}}</td>
                    <td style="color:{{ $asset->location->icon ?? '#666'}};">{{ $asset->location->name ?? 'Unallocated'}}</td>
                    <td>{{ $asset->supplier->name ?? 'N/A'}}</td>
                    <td align="center">{{\Carbon\Carbon::parse($asset->purchased_date)->format("d/m/Y")}}</td>
                    <td align="center">£{{ $asset->purchased_cost}}</td>
                    <td align="center">{{ $asset->warranty}}</td>
                    <td align="center">
                    {{ $asset->warranty }} Months
                    @if($asset->warranty != 0)
                    @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                    <br><span class="small">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</span>
                    @endif
                    </td>
                </tr>
        @endforeach
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Item</small></th>
                <th><small>Tag</small></th>
                <th><small>Supplier</small></th>
                <th><small>Date</small></th>
                <th ><small>Cost</small></th>
                <th ><small>Warranty (M)</small></th>
                <th class="text-center"><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
</section>
@endif

@if($manufacturer->accessory()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Accessories</th>
            </tr>
        <tr>
            <th><small>Name</small></th>
            <th align="center"><small>Location</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th align="center"><small>Status</small></th>
            <th align="center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($manufacturer->accessory as $accessory)

        <tr>
            <td>{{ $accessory->name}}<br>
                <span class="small">{{ $accessory->serial_no ?? 'N/A'}}</small></td>
            <td style="color:{{ $accessory->location->icon ?? '#666'}};">{{ $accessory->location->name ?? 'Unnallocated'}}</td>
            <td align="center">{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
            <td align="center">£{{ $accessory->purchased_cost}}</td>
            <td align="center">{{ $accessory->supplier->name ?? 'N/A'}}</td>
            <td align="center">{{ $accessory->status->name ?? 'N/A'}}</td>
            <td align="center">
            {{ $accessory->warranty }} Months
            @if($accessory->warranty != 0)
            @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
            <br><span class="small">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</span>
            @endif
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th align="center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th align="center"><small>Status</small></th>
                <th align="center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</section>
@endif
@if($manufacturer->component()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="7">Components</th>
        </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Location</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($manufacturer->component as $component)
        <tr>
            <td>{{ $component->name}}<br>
                <span class="small">{{ $component->serial_no ?? 'N/A'}}</small></td>
            <td style="color:{{ $component->location->icon ?? '#666'}};">{{ $component->location->name ?? 'Unnallocated'}}</td>
            <td align="center">{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
            <td align="center">£{{ $component->purchased_cost}}</td>
            <td align="center">{{ $component->supplier->name ?? 'N/A'}}</td>
            <td align="center">{{ $component->status->name ?? 'N/A'}}</td>
            <td align="center">
            {{ $component->warranty }} Months
            @if($component->warranty != 0)
            @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
            <br><span class="small">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</span>
            @endif
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
@if($manufacturer->consumable()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Consumables</th>
            </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Location</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($manufacturer->consumable as $consumable)
        <tr>
            <td>{{ $consumable->name}}<br>
                <span class="small">{{ $consumable->serial_no ?? 'N/A'}}</small></td>
            <td style="color:{{ $consumable->location->icon ?? '#666'}};">{{ $consumable->location->name ?? 'Unnallocated'}}</td>
            <td align="center">{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
            <td align="center">£{{ $consumable->purchased_cost}}</td>
            <td align="center">{{ $consumable->supplier->name ?? 'N/A'}}</td>
            <td align="center">{{ $consumable->status->name ?? 'N/A'}}</td>
            <td align="center">
            {{ $consumable->warranty }} Months
            @if($consumable->warranty != 0)
            @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
            <br><span class="small">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</span>
            @endif
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

@if($manufacturer->miscellanea()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Miscellanous</th>
            </tr>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Location</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($manufacturer->miscellanea as $miscellanea)
        <tr>
            <td>{{ $miscellanea->name}}<br>
                <span class="small">{{ $miscellanea->serial_no ?? 'N/A'}}</small></td>
            <td style="color:{{ $miscellanea->location->icon ?? '#666'}};">{{ $miscellanea->location->name ?? 'Unnallocated'}}</td>
            <td align="center">{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
            <td align="center">£{{ $miscellanea->purchased_cost}}</td>
            <td align="center">{{ $miscellanea->supplier->name ?? 'N/A'}}</td>
            <td align="center">{{ $miscellanea->status->name ?? 'N/A'}}</td>
            <td align="center">
            {{ $miscellanea->warranty }} Months
            @if($miscellanea->warranty != 0)
            @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
            <br><span class="small">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</span>
            @endif
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