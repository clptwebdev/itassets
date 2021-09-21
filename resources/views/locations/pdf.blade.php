@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Locations')

@section('user', $user->name)

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
                <td style="color:{{ $location['color']}}">{{ $location['name']}}</td>
                <td><span class="small">{{ $location['line1'].', '.$location['line2'].', '.$location['city'].', '.$location['county'].', '.$location['postcode']}}</span></td>
                <td align="center">{{$location['asset']}}</td>
                <td align="center">{{$location['accessory']}}</td>
                <td align="center">{{$location['component']}}</td>
                <td align="center">{{$location['consumable']}}</td>
                <td align="center">{{$location['miscellaneous']}}</td>
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