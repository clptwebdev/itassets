@extends('layouts.pdf-reports')

@section('title', 'Licenses Report')

@section('page', 'Licenses')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Name</th>
            <th align="center">Supplier</th>
            <th align="center">Location</th>
            <th align="center">Cost</th>
            <th align="center">Expiry</th>
            <th align="center">contact</th>
        </tr>
        @foreach($licenses as $id=>$license)
            <tr>
                <td>{{ $license['name'] ?? 'N/A'}}</td>
                <td>{{ $license['supplier'] ?? 'N/A'}}</td>
                <td><span>{{$license['location'] ?? 'N/A'}}</span></td>
                <td align="center">£{{number_format( (float) $license['purchased_cost'], 2, '.', ',' ) ?? 'N/A'}}</td>
                <td align="center">{{ $license['expiry'] ?? 'N/A'}}</td>
                <td align="center">{{ $license['contact'] ?? 'N/A'}}</td>
            </tr>
        @endforeach
    </table>
@endsection



