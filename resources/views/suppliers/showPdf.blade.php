@extends('layouts.pdf-reports')

@section('title', 'Supplier Report')

@section('page', $supplier->name)

@section('content')
<section>
<table class="table  table-bordered p-1 mb-4" width="100%">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">Supplier Information</th>
        </tr>
    </thead>
    <tr>
        <td rowspan="4" width="15%">
            @if($supplier->photo()->exists())
                <img src="{{ asset($supplier->photo->path)}}" width="100%" alt="{{$supplier->name}}">
            @else
                <img src="{{asset('images/png/manufacturer_image.png')}}" width="100%" alt="{{$supplier->name}}">
            @endif
        </td>
        <td>{{ $supplier->name }}</td>
    </tr>
    <tr><td>
        {{ $supplier->name }}<br>
        <p>{{ $supplier->address_1 }}<br>
            @if($supplier->address_2 != "")
            {{ $supplier->address_2 }}<br>
            @endif
            {{ $supplier->city }}<br>
            {{ $supplier->postcode }}</p>
        <p>Tel: {{ $supplier->telephone }}</p>
        @if($supplier->fax != "")
        {{ $supplier->fax }}<br>
        @endif
        <p>Email: {{ $supplier->email }}</p>
        <p>URL: {{ $supplier->url }}</p>    
    </td></tr>
    <tr><td>{{ $supplier->supportUrl }}</td></tr>
    <tr><td>{{ $supplier->supportPhone }}</td></tr>
    <tr><td>{{ $supplier->supportEmail }}</td></tr>
</table>
</section>
<hr>
<section>
<table class="table table-bordered">
    <tr>
        <td class="text-center d-none d-xl-table-cell">{{ $supplier->asset->count() }}                                </td>
        <td class="text-center d-none d-xl-table-cell">{{$supplier->accessory->count() ?? "N/A"}}</td>
        <td class="text-center d-none d-xl-table-cell">{{$supplier->component->count() ?? "N/A"}}</td>
        <td class="text-center d-none d-xl-table-cell">{{$supplier->consumable->count() ?? "N/A"}}</td>
        <td class="text-center d-none d-xl-table-cell">{{$supplier->miscellanea->count() ?? "N/A"}}</td>
    </tr>
</table>

</section>
{{-- @if($supplier->asset()->exists())
<section>
    <table width="100%" class="table table-striped table-bordered">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Assets</th>
            </tr>
        <tr>
            <th width="20%;">Item</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Location</th>
            <th width="10%;">Date</th>
            <th width="10%;">Cost</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>
        
        <tbody>
            @foreach($supplier->asset as $asset)
            <tr>
                <td>{{$asset->name}}<br>{{ $asset->model->name ?? 'No Model'}}<br><small class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                <td align="center">
                    {!! '<div id="barcode"><img width="120px" height="30px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   /></div>' !!}
                    <span style="font-weight: 800">{{ $asset->asset_tag }}</span></td>
                <td class="text-center" style="color: {{$asset->location->icon ?? '#666'}}">{{ $asset->location->name ?? 'Unallocated'}}</td>
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
                <th><small>Location</small></th>
                <th><small>Date</small></th>
                <th ><small>Cost</small></th>
                <th ><small>Warranty (M)</small></th>
                <th class="text-center"><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
</section>
@endif
@if($supplier->accessory()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Accessories</th>
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
        @foreach($supplier->accessory as $accessory)

            <tr>
                <td>{{$accessory->name}}
                    <br>
                    <small>{{$accessory->serial_no}}</small>
                </td>
                <td class="text-center" style="color: {{$accessory->location->icon ?? '#666'}}">{{ $accessory->location->name ?? 'Unallocated'}}</td>
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
                <th class="text-center"><small>Location</small></th>
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
@if($supplier->component()->exists())
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
        @foreach($supplier->component as $component)

            <tr>
                <td>{{$component->name}}
                    <br>
                    <small>{{$component->serial_no}}</small>
                </td>
                <td class="text-center" style="color: {{$component->location->icon ?? '#666'}}">{{ $component->location->name ?? 'Unallocated'}}</td>
                <td>{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$component->purchased_cost}}</td>
                <td>{{$component->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$component->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
                <td class="text-center" data-sort="{{ $warranty_end }}">
                    {{ $component->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Location</small></th>
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
@if($supplier->consumable()->exists())
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
        @foreach($supplier->consumable as $consumable)

            <tr>
                <td>{{$consumable->name}}
                    <br>
                    <small>{{$consumable->serial_no}}</small>
                </td>
                <td class="text-center" style="color: {{$consumable->location->icon ?? '#666'}}">{{ $consumable->location->name ?? 'Unallocated'}}</td>
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
                <th class="text-center"><small>Location</small></th>
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
@if($supplier->miscellanea()->exists())
<div class="page-break"></div>
<section>
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="7">Miscellaneous</th>
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
        @foreach($supplier->miscellanea as $miscellanea)

            <tr>
                <td>{{$miscellanea->name}}
                    <br>
                    <small>{{$miscellanea->serial_no}}</small>
                </td>
                <td class="text-center" style="color: {{$miscellanea->location->icon ?? '#666'}}">{{ $miscellanea->location->name ?? 'Unallocated'}}</td>
                <td>{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$miscellanea->purchased_cost}}</td>
                <td>{{$miscellanea->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$miscellanea->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
                <td class="text-center" data-sort="{{ $warranty_end }}">
                    {{ $miscellanea->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Location</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</section> 
@endif--}}
@endsection