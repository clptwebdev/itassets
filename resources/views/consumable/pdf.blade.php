@extends('layouts.pdf-reports')

@section('title', 'Consumables Report')

@section('page', 'Consumables')

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
        @foreach($consumables as $consumable)

            <tr>
                <td>{{$consumable->name}}
                    <br>
                    <small>{{$consumable->serial_no}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $consumable->location->icon ?? '#666'}}">{{$consumable->location->name ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$consumable->purchased_cost}}</td>
                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$consumable->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
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
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
</body>
</html>