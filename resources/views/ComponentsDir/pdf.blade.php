@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Components')

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
        @foreach($components as $component)

            <tr>
                <td>{{$component['name'] }}
                    <br>
                    <small>{{$component['serial_no']}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $component['icon'] ?? '#666'}}">{{$component['location'] ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$component['manufacturer'] ?? "N/A"}}</td>
                <td>{{$component['purchased_date']}}</td>
                <td>£{{$component['purchased_cost']}}</td>
                <td>{{$component['supplier'] ?? 'N/A'}}</td>
                <td class="text-center"><span style="color:{{ $component['color']}};">{{$component['status'] ??'N/A'}}</span></td>
                <td class="text-center">{{ $component['warranty'] }} Months</small>
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