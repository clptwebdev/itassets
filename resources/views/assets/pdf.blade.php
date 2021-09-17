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
        @foreach($assets as $obj)
        <?php $asset = \App\Models\Asset::find($obj->id);?>
            <tr>
                <td>{{ $asset->name }}<br><small>{{ $asset->model->name ?? 'No Model' }}</small></td>
                <td class="text-center">
                    <span style="color: {{ $asset->location->icon ?? '#666'}}">{{$asset->location->name ?? 'Unassigned'}}</span>
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
