@extends('layouts.pdf-reports')

@section('title', 'Asset  Report')

@section('page', 'Assets')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <tr>
            <th align="center">Item</th>
            <th align="center">Location</th>
            <th align="center">Tag</th>
            <th align="center">Manufacturer</th>
            <th align="center">Date</th>
            <th align="center">Cost</th>
            <th align="center">Supplier</th>
            <th align="center">Warranty (M)</th>
            <th align="center">Audit Due</th>
        </tr>
        @foreach($assets as $id=>$asset)
        <tr>
            <td>{{ $asset['name'] ?? 'N/A'}}<br>{{ $asset['model'] ?? 'N/A'}}</td>
            <td><span >{{$asset['location'] ?? 'N/A'}}</span></td>
            <td align="center">#{{ $asset['asset_tag'] ?? 'N/A'}}</td>
            <td>{{ $asset['manufacturer'] ?? 'N/A'}}</td>
            <td align="center">{{ $asset['purchased_date'] ?? 'N/A'}}</td>
            <td align="center">{{ $asset['purchased_cost'] ?? 'N/A'}} @if($asset['donated'] == 1)<span class="text-success text-sm">Donated</span>@endif
                <br><small>(*£{{ number_format($asset['depreciation'], 2)}})</small></td>
            <td>{{ $asset['supplier'] ?? 'N/A'}}</td>
            <td align="center">{{ $asset['warranty'] ?? 'N/A'}}</td>
            <td align="center">{{ $asset['audit'] ?? 'N/A'}}</td>
        </tr>
        @endforeach
    </table>
@endsection
