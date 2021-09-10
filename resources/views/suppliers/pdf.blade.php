@extends('layouts.pdf-reports')

@section('title', 'Manufacturer Report')

@section('page', 'Manufacturers')

@section('content')
    <table id="assetsTable" class="table table-striped" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th><small>URL</small></th>
            <th><small>Email</small></th>
            <th><small>Telephone</small></th>
            <th><small>Assets</small></th>
            <th><small>Accessories</small></th>
            <th><small>Components</small></th>
            <th><small>Consumables</small></th>
            <th><small>Misc</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($suppliers as $supplier)

            <tr>
                <td>{{ $supplier->name}}</td>
                <td><small>{{ $supplier->address_1.', '.$supplier->address_2.', '.$supplier->city.', '.$supplier->county.', '.$supplier->postcode}}</small></td>
                <td><small>{{ $supplier->email}}<br>{{ $supplier->telephone}}</small></td>
                <td><small>{{ $supplier->url}}</small></td>
                <td class="text-center"> {{ $supplier->asset->count() ?? 'N/A'}}</td>
                <td class="text-center">{{$supplier->accessory->count() ?? "N/A"}}</td>
                <td class="text-center">{{$supplier->component->count() ?? "N/A"}}</td>
                <td class="text-center">{{$supplier->consumable->count() ?? "N/A"}}</td>
                <td class="text-center">{{$supplier->miscellanea->count() ?? "N/A"}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th><small>URL</small></th>
                <th><small>Email</small></th>
                <th><small>Telephone</small></th>
                <th><small>Assets</small></th>
                <th><small>Accessories</small></th>
                <th><small>Components</small></th>
                <th><small>Consumables</small></th>
                <th><small>Misc</small></th>
            </tr>
            </tfoot>
    </table>
@endsection