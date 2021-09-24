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
            <td>{{ $asset['name']}}<br>{{ $asset['model']}}</td>
            <td><span style="color: {{ $asset['icon']}}">{{$asset['location']}}</span></td>
            <td align="center">#</td>
            <td>{{ $asset['manufacturer']}}</td>
            <td align="center">{{ $asset['purchased_date'] ?? 'N/A'}}</td>
            <td align="center">{{ $asset['purchased_cost']}}</td>
            <td>{{ $asset['supplier']}}</td>
            <td align="center">{{ $asset['warranty']}}</td>
            <td align="center">{{ $asset['audit'] ?? 'N/A'}}</td>
        </tr>
        @endforeach
    </table>
@endsection
