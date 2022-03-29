@extends('layouts.pdf-reports')

@section('title', 'Software Report')

@section('page', 'Software')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Supplier</th>
            <th align="center">Location</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Depreciation (Years)</th>
        </tr>
        @foreach($softwares as $id=>$software)
            <tr>
                <td>{{ $software['name'] ?? 'N/A'}}</td>
                <td>{{ $software['supplier'] ?? 'N/A'}}</td>
                <td><span>{{$software['location'] ?? 'N/A'}}</span></td>
                <td align="center">{{ $software['purchased_date'] ?? 'N/A'}}</td>
                <td align="center">£{{number_format( (float) $software['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
                <td align="center">{{ $software['depreciation'] ?? 'N/A'}}</td>
            </tr>
        @endforeach
    </table>
@endsection



