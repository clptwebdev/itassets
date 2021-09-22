@extends('layouts.pdf-reports')

@section('title', 'Asset Models Report')

@section('page', $assetModel->name)

@section('user', $user->name)

@section('content')

    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Device Information</th>
            </tr>
        </thead>
        <tr>
            <td rowspan="4" width="20%">
            @if($assetModel->photo()->exists())
                <img src="{{ asset($assetModel->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" class="p-3" alt="{{$assetModel->name}}">
            @else
            <span style="width: 100px; height: 100px; background-colour: #222;">No Image Available</span>
            @endif
            </td>
            <td>Name:</td>
        </tr>
        <tr>
            <td>{{ $assetModel->name }}</td>
        </tr>
        <tr>
            <td>Device Model N<span class="">o</span></td>
        </tr>
        <tr>
            <td>{{ $assetModel->model_no }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th >Depreciation Model </th>
        </tr>
        </thead>
        <tr>
            <td><strong>{{ $assetModel->depreciation->name ?? 'No Deprecatiation Set'}}</strong></td>
        </tr>
    </table>

    <table class="table">
        <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th >EOL (End of Life) </th>
        </tr>
        </thead>
        <tr>
            <td><strong>{{ $assetModel->eol }} Months</strong></td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th>Notes:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $assetModel->notes}}</td>
            </tr>
        </tbody>
    </table>

    @if($assetModel->manufacturer()->exists())
    <?php $manufacturer = $assetModel->manufacturer; ?>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Device Information</th>
            </tr>
        </thead>
        <tr>
            <td rowspan="4" width="20%">
            @if($manufacturer->photo()->exists())
                <img src="{{ asset($manufacturer->photo->path)}}"
                    width="100%" alt="{{$manufacturer->name}}">
            @else
            <span style="width: 100px; height: 100px; background-colour: #222;">No Image Available</span>
            @endif
            </td>
            <td>Name:</td>
        </tr>
        <tr>
            <td>{{ $manufacturer->name }}</td>
        </tr>
        <tr>
            <td>Details:</td>
        </tr>
        <tr>
            <td>
                <p>Tel: {{ $manufacturer->supportPhone }}</p>
                <p>Email: {{ $manufacturer->supportEmail }}</p>
                <p>URL: {{ $manufacturer->supportUrl }}</p>    
            </td>
        </tr>
    </table>
    @endif
    

@if($assetModel->assets()->exists())
<div class="page-break"></div>
<table class="table ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th colspan="5">Assigned Assets</th></tr>    
    </thead>                      
    <tbody>
            <tr>
                <th width="30%"><small>Item</small></th>
                <th width="10%"><small>Tag</small></th>
                <th width="40%"><small>Location</small></th>
                <th width="10%"><small>Date</small></th>
                <th width="10%"><small>Cost</small></th>
            </tr>
            @foreach($assetModel->assets as $asset)
            <tr>
                <td>{{ $asset->name }}<br>{{ $asset->serial_no}}</th>
                <td>{{ $asset->asset_tag }}</td>
                <td><span style="color:{{ $asset->location->icon ?? '#666'}};">{{ $asset->location->name ?? 'Unallocated' }}</th>
                <td>{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                <td>£{{ $asset->purchased_cost }}</td>
            </tr>
            @endforeach
        </tbody>
</table>
@endif
@endsection