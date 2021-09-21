@extends('layouts.pdf-reports')

@section('title', 'Manufacturer Report')

@section('user', $user->name)

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
                <td>{{ $manufacturer['name']}}</td>
                <td><small>{{ $manufacturer['url']}}</small></td>
                <td><small>{{ $manufacturer['email']}}</small></td>
                <td><small>{{ $manufacturer['telephone']}}</small></td>
                <td class="text-center">{{ $manufacturer['asset']}}</td>
                <td class="text-center">{{$manufacturer['accessory']}}</td>
                <td class="text-center">{{$manufacturer['component']}}</td>
                <td class="text-center">{{$manufacturer['consumable']}}</td>
                <td class="text-center">{{$manufacturer['miscellaneous']}}</td>
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