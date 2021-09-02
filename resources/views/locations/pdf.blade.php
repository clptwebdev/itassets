@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Locations')

@section('content')
    <table id="assetsTable" class="table table-striped" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th><small>Address</small></th>
            <th><small>Assets</small></th>
            <th><small>Accessories</small></th>
            <th><small>Components</small></th>
            <th><small>Consumables</small></th>
            <th><small>Misc</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($locations as $location)

            <tr>
                <td style="color:{{ $location->icon}}">{{ $location->name}}</td>
                <td><small>{{ $location->address_1.', '.$location->address_2.', '.$location->city.', '.$location->county.', '.$location->postcode}}</small></td>
                <td class="text-center">{{$location->asset->count() ?? "N/A"}}</td>
                <td class="text-center">{{$location->accessory->count() ?? "N/A"}}</td>
                <td class="text-center">{{$location->component->count() ?? "N/A"}}</td>
                <td class="text-center">{{$location->consumable->count() ?? "N/A"}}</td>
                <td class="text-center">N/A</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
            <th><small>Address</small></th>
            <th><small>Assets</small></th>
            <th><small>Accessories</small></th>
            <th><small>Components</small></th>
            <th><small>Consumables</small></th>
            <th><small>Misc</small></th>
            </tr>
            </tfoot>
    </table>
@endsection