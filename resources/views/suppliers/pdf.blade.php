@extends('layouts.pdf-reports')

@section('title', 'Suppliers Report')

@section('user', $user->name)

@section('page', 'Suppliers')

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
                <td>
                    {{ $supplier['name']}}<br>
                    <span class="small">{{ $supplier['line1'].', '.$supplier['line2'].', '.$supplier['city'].', '.$supplier['county'].', '.$supplier['postcode']}}</span>
                </td>
                <td><small>{{ $supplier['url']}}</small></td>
                <td><small>{{ $supplier['email']}}</small></td>
                <td><small>{{ $supplier['telephone']}}</small></td>
                <td class="text-center">{{ $supplier['asset']}}</td>
                <td class="text-center">{{$supplier['accessory']}}</td>
                <td class="text-center">{{$supplier['component']}}</td>
                <td class="text-center">{{$supplier['consumable']}}</td>
                <td class="text-center">{{$supplier['miscellaneous']}}</td>
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