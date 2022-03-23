@extends('layouts.pdf-reports')

@section('title', 'Properties Report')

@section('page', 'Properties')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Type</th>
            <th align="center">Location</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Depreciation</th>
            <th align="center">Current Value</th>
        </tr>
        @foreach($properties as $id=>$property)
        <tr>
            <td>{{ $property['name'] ?? 'N/A'}}</td>
            <td>{{ $property['type'] ?? 'N/A'}}</td>
            <td><span >{{$property['location'] ?? 'N/A'}}</span></td>
            <td align="center">{{ $property['purchased_date'] ?? 'N/A'}}</td>
            <td align="center">£{{number_format( (float) $property['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
            <td align="center">{{ $property['depreciation'] ?? 'N/A'}}</td>
            <td align="center">£{{number_format( (float) $property['current_value'], 2, '.', ',' ) ?? 'N/A'}}</td>
        </tr>
        @endforeach
    </table>
@endsection
