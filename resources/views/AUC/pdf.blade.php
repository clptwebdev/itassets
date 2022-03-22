@extends('layouts.pdf-reports')

@section('title', 'Assets Under Construction Report')

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
        @foreach($aucs as $id=>$auc)
        <tr>
            <td>{{ $auc['name'] ?? 'N/A'}}</td>
            <td>{{ $auc['type'] ?? 'N/A'}}</td>
            <td><span >{{$auc['location'] ?? 'N/A'}}</span></td>
            <td align="center">{{ $auc['purchased_date'] ?? 'N/A'}}</td>
            <td align="center">{{ $auc['purchased_cost'] ?? 'N/A'}}</td>
            <td align="center">{{ $auc['depreciation'] ?? 'N/A'}}</td>
            <td align="center">£{{ $auc['current_value'] ?? 'N/A'}}</td>
        </tr>
        @endforeach
    </table>
@endsection
