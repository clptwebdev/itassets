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
        </tr>
        @endforeach
    </table>
@endsection
