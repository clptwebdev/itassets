@extends('layouts.pdf-reports')

@section('title', 'FFE Report')

@section('page', 'Furniture, Fixtures and Equipment')

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
        @foreach($ffes as $ffe)

            <tr>
                <td>{{$ffe['name'] }}
                    <br>
                    <small>{{$ffe['serial_no']}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $ffe['icon'] ?? '#666'}}">{{$ffe['location'] ?? 'Unassigned'}}</span>
                    @if($ffe['room'] != "")<br><small>{{ $ffe['room'] ?? 'N/A'}}</small>@endif</td>
                <td class="text-center">{{$ffe['manufacturer'] ?? "N/A"}}</td>
                <td>{{$ffe['purchased_date']}}</td>
                <td class="text-center">
                    {{$ffe['purchased_cost']}} @if($ffe['donated'] == 1)<span class="text-success text-sm">Donated</span>@endif<br>
                    <small>(*£{{ number_format($ffe['depreciation'], 2)}})</small>
                </td>
                <td>{{$ffe['supplier'] ?? 'N/A'}}</td>
                <td class="text-center"><span style="color:{{ $ffe['color']}};">{{$ffe['status'] ??'N/A'}}</span></td>
                <td class="text-center">{{ $ffe['warranty'] }} Months</small>
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