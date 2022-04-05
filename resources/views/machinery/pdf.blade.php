@extends('layouts.pdf-reports')

@section('title', 'Vehicle Report')

@section('page', 'Vehicle')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Registration</th>
            <th align="center">Supplier</th>
            <th align="center">Location</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Depreciation (Years)</th>
        </tr>
        @foreach($vehicles as $id=>$vehicle)
            <tr>
                <td>{{ $vehicle['name'] ?? 'N/A'}}</td>
                <td>{{ $vehicle['registration'] ?? 'N/A'}}</td>
                <td>{{ $vehicle['supplier'] ?? 'N/A'}}</td>
                <td><span>{{$vehicle['location'] ?? 'N/A'}}</span></td>
                <td align="center">{{ $vehicle['purchased_date'] ?? 'N/A'}}</td>
                <td align="center">£{{number_format( (float) $vehicle['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
                <td align="center">{{ $vehicle['depreciation'] ?? 'N/A'}}</td>
            </tr>
        @endforeach
    </table>
@endsection



