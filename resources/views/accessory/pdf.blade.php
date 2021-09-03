@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Accessories')

@section('content')
    <table id="assetsTable" class="table table-striped" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Location</small></th>
            <th class="text-center"><small>Manufacturers</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($accessories as $accessory)

            <tr>
                <td>{{$accessory->name}}
                    <br>
                    <small>{{$accessory->serial_no}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $accessory->location->icon ?? '#666'}}">{{$accessory->location->name ?? 'Unassigned'}}</span></td>
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
                <th class="text-center"><small>Location</small></th>
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
    @endsection