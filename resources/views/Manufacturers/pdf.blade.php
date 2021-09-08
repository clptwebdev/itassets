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
        @foreach($manufacturers as $manufacturer)

            <tr>
                <td>{{ $manufacturer->name}}</td>
                <td><small>{{ $manufacturer->supportUrl}}</small></td>
                <td><small>{{ $manufacturer->supportEmail}}</small></td>
                <td><small>{{ $manufacturer->supportPhone}}</small></td>
                <td class="text-center">
                    @php
                        $total = 0;
                        foreach($manufacturer->assetModel as $assetModel){
                            $total += $assetModel->assets->count();
                        }   
                    @endphp
                    {{ $total}}
                </td>
                <td class="text-center">{{$manufacturer->accessory->count() ?? "N/A"}}</td>
                <td class="text-center">{{$manufacturer->component->count() ?? "N/A"}}</td>
                <td class="text-center">{{$manufacturer->consumable->count() ?? "N/A"}}</td>
                <td class="text-center">{{$manufacturer->miscellanea->count() ?? "N/A"}}</td>
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