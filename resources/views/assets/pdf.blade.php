@extends('layouts.pdf-reports')

@section('title', 'Asset  Report')

@section('page', 'Assets')

@section('user', $user->name)

@section('content')
    <table id="assetsTable" width="100%">
        <thead>
        <tr>
            <th width="15%;">Item</th>
            <th width="15%;">Location</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Manufacturer</th>
            <th width="5%;">Date</th>
            <th width="5%;">Cost</th>
            <th width="10%;">Supplier</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>

        <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->name }}<br><small>{{ $asset->model ?? 'No Model' }}</small></td>
                <td class="text-center"><span style="color:{{ $asset->icon ?? '#666' }};">{{ $asset->location ?? 'No Location' }}</span></td>
                <td class="text-center">
                    {!! '<img width="100%" height="100px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39',3,33) . '" alt="barcode"   />' !!}
                    <br>Asset Tag: #{{ $asset->asset_tag }}   
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Item</small></th>
                <th><small>Location</small></th>
                <th><small>Tag</small></th>
                <th><small>Manufacturer</small></th>
                <th><small>Date</small></th>
                <th><small>Cost</small></th>
                <th><small>Supplier</small></th>
                <th><small>Warranty (M)</small></th>
                <th><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
@endsection
