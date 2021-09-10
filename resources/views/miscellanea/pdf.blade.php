@extends('layouts.pdf-reports')

@section('title', 'Miscellaneous Report')

@section('page', 'Miscellaneous')

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
        @foreach($miscellaneous as $miscellanea)

            <tr>
                <td>{{$miscellanea->name}}
                    <br>
                    <small>{{$miscellanea->serial_no}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $miscellanea->location->icon ?? '#666'}}">{{$miscellanea->location->name ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$miscellanea->manufacturer->name ?? "N/A"}}</td>
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