@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', 'Miscellaneous')

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
        @foreach($miscellaneous as $miscellanea)

            <tr>
                <td>{{$miscellanea['name'] }}
                    <br>
                    <small>{{$miscellanea['serial_no']}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $miscellanea['icon'] ?? '#666'}}">{{$miscellanea['location'] ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$miscellanea['manufacturer'] ?? "N/A"}}</td>
                <td>{{$miscellanea['purchased_date']}}</td>
                <td class="text-center">
                    £{{$miscellanea['purchased_cost']}} @if($miscellanea['donated'] == 1)<span class="text-success text-sm">Donated</span>@endif
                    <small>(*£{{ number_format($miscellanea['depreciation'], 2)}})</small>
                <td>{{$miscellanea['supplier'] ?? 'N/A'}}</td>
                <td class="text-center"><span style="color:{{ $miscellanea['color']}};">{{$miscellanea['status'] ??'N/A'}}</span></td>
                <td class="text-center">{{ $miscellanea['warranty'] }} Months</small>
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