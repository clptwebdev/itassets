@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Consumables')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th><small>Location</small></th>
            <th><small>Manufacturers</small></th>
            <th align="center"><small>Purchased Date</small></th>
            <th align="center"><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th align="center"><small>Status</small></th>
            <th align="center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($consumables as $consumable)

            <tr>
                <td>{{$consumable['name'] }}
                    <br>
                    <small>{{$consumable['serial_no']}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $consumable['icon'] ?? '#666'}}">{{$consumable['location'] ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$consumable['manufacturer'] ?? "N/A"}}</td>
                <td>{{$consumable['purchased_date']}}</td>
                <td>£{{$consumable['purchased_cost']}}</td>
                <td>{{$consumable['supplier'] ?? 'N/A'}}</td>
                <td class="text-center"><span style="color:{{ $consumable['color']}};">{{$consumable['status'] ??'N/A'}}</span></td>
                <td class="text-center">{{ $consumable['warranty'] }} Months</small>
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