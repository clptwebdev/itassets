@extends('layouts.pdf-reports')

@section('title', 'Asset  Report')

@section('page', 'Assets')

@section('user', $user->name)

@section('content')
    <table id="assetsTable" width="100%">
        <thead>
        <tr>
            <th width="15%;">Item</th>
            <th width="15%;">Location</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Manufacturer</th>
            <th width="5%;">Date</th>
            <th width="5%;">Cost</th>
            <th width="10%;">Supplier</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>

        <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->name }}<br><small>{{ $asset->model->name ?? 'No Model' }}</small></td>
                <td class="text-center">
                    @if($location = \App\Models\Location::find($asset->location_id))
                    <span style="color: {{ $location->icon ?? '#666'}}">{{$location->name ?? 'Unassigned'}}</span>
                    @else
                       {{ 'Unallocated'}} 
                    @endif
                </td>
                <td align="center">
                    {!! '<span id="barcode"><img width="120px" height="30px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   /></span>' !!}
                    <span style="font-weight: 800">{{ $asset->asset_tag }}</span></td>
                <td class="text-center">
                    {{ $asset->model->manufacturer->name ?? 'N/A' }}
                </td>
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
                <td class="text-center">{{$asset->supplier->name ?? "N/A"}}</td>
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
                <th><small>Location</small></th>
                <th><small>Tag</small></th>
                <th><small>Manufacturer</small></th>
                <th><small>Date</small></th>
                <th><small>Cost</small></th>
                <th><small>Supplier</small></th>
                <th><small>Warranty (M)</small></th>
                <th><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
@endsection
