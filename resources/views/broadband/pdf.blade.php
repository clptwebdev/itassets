@extends('layouts.pdf-reports')

@section('title', 'Broadband Report')

@section('page', 'Broadband')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Supplier</th>
            <th align="center">Location</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Renewal Date</th>
            <th align="center">Package</th>
        </tr>
        @foreach($broadbands as $id=>$broadband)
            <tr>
                <td>{{ $broadband['name'] ?? 'N/A'}}</td>
                <td>{{ $broadband['supplier'] ?? 'N/A'}}</td>
                <td><span>{{$broadband['location'] ?? 'N/A'}}</span></td>
                <td align="center">{{ $broadband['purchased_date'] ?? 'N/A'}}</td>
                <td align="center">£{{number_format( (float) $broadband['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
                <td align="center">{{ $broadband['renewal_date'] ?? 'N/A'}}</td>
                <td align="center">{{ $broadband['package'] ?? 'N/A'}}</td>
            </tr>
        @endforeach
    </table>
@endsection



