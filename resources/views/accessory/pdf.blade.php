@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Accessories')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th><small>Location</small></th>
            <th><small>Manufacturers</small></th>
            <th align="center"><small>Purchased Date</small></th>
            <th align="center"><small>Cost (Value)</small></th>
            <th><small>Supplier</small></th>
            <th align="center"><small>Status</small></th>
            <th align="center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($accessories as $accessory)

            <tr>
                <td>{{$accessory['name'] }}
                    <br>
                    <small>{{$accessory['serial_no']}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $accessory['icon'] ?? '#666'}}">{{$accessory['location'] ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{ $accessory['model']}}<br><small>{{$accessory['manufacturer'] ?? "N/A"}}</small></td>
                <td>{{$accessory['purchased_date']}}</td>
                <td class="text-center">
                    £{{$accessory['purchased_cost']}}
                    <small>(*£{{ number_format($accessory['depreciation'], 2)}})</small>
                </td>
                <td>{{$accessory['supplier'] ?? 'N/A'}}</td>
                <td class="text-center"><span style="color:{{ $accessory['color']}};">{{$accessory['status'] ??'N/A'}}</span></td>
                <td class="text-center">{{ $accessory['warranty'] }} Months</small>
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
                <th><small>Cost (Value)</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
    @endsection