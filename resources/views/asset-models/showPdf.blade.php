@extends('layouts.pdf-reports')

@section('title', 'Asset Models Report')

@section('page', 'Asset Models')

@section('user', $user->name)

@section('content')

        <div style="width: 62%; pading-right: 3%; float: left;">
            @if($assetModel->photo()->exists())
                <img src="{{ asset($assetModel->photo->path) ?? asset('images/svg/device-image.svg')}}" width="200px" class="p-3" alt="{{$assetModel->name}}">
            @else
                <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$assetModel->name}}">
            @endif
            <hr>
            <table id="assetstable" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Device Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Name:</td>
                    <td>{{ $assetModel->name }}</td>
                </tr>
                <tr>
                <tr>
                    <td>Device Model N<span class="">o</span></td>
                    <td>{{ $assetModel->model_no }}</td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th >Depreciation Model </th>
                </tr>
                </thead>
                <tr>
                    <td><strong>{{ $assetModel->depreciation->name }}</strong></td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th >EOL (End of Life) </th>
                </tr>
                </thead>
                <tr>
                    <td><strong>{{ $assetModel->eol }} Months</strong></td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
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

        </div>
        <div style="width: 32%; padding-left: 3%;float: right; border-left: solid 3px #CCC;">
            <?php $manufacturer = $assetModel->manufacturer; ?>
            <div class="text-center">
            @if(isset($manufacturer->photo->path))
            <img src="{{ asset($manufacturer->photo->path)}}"
                width="70%" alt="{{$manufacturer->name}}">
            @endif
            </div>
            <p><strong>{{ $manufacturer->name }}</strong></p>
            <p>Tel: {{ $manufacturer->supportPhone }}</p>
            <p>Email: {{ $manufacturer->supportEmail }}</p>
            <p>URL: {{ $manufacturer->supportUrl }}</p>
        </div>
    </div>
    

@if($assetModel->assets()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
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
                <td>{{ $assetModel->name }}</th>
                <td>{{ $asset->asset_tag }}</td>
                <td><span style="color:{{ $asset->location->icon}};">{{ $asset->location->name }}</th>
                <td>{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                <td>£{{ $asset->purchased_cost }}</td>
            </tr>
            @endforeach
        </tbody>
</table>
@endif
@endsection