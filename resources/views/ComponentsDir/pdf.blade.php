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
        @foreach($components as $component)

            <tr>
                <td>{{$component->name}}
                    <br>
                    <small>{{$component->serial_no}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $component->location->icon ?? '#666'}}">{{$component->location->name ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$component->manufacturer->name ?? "N/A"}}</td>
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