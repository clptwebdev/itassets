@extends('layouts.pdf-reports')

@section('title', 'Machinery Report')

@section('page', 'Machinery')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Description</th>
            <th align="center">Supplier</th>
            <th align="center">Location</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Depreciation (Years)</th>
        </tr>
        @foreach($machineries as $id=>$machinery)
            <tr>
                <td>{{ $machinery['name'] ?? 'N/A'}}</td>
                <td>{{ $machinery['description'] ?? 'N/A'}}</td>
                <td>{{ $machinery['supplier'] ?? 'N/A'}}</td>
                <td><span>{{$machinery['location'] ?? 'N/A'}}</span></td>
                <td align="center">{{ $machinery['purchased_date'] ?? 'N/A'}}</td>
                <td align="center">£{{number_format( (float) $machinery['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
                <td align="center">{{ $machinery['depreciation'] ?? 'N/A'}}</td>
            </tr>
        @endforeach
    </table>
@endsection



