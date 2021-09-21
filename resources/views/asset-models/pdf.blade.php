@extends('layouts.pdf-reports')

@section('title', 'Asset  Report')

@section('page', 'Assets')

@section('user', $user->name)

@section('content')
<table id="assetsTable" width="100%" class="table table-striped">
    <thead>
    <tr>
        <th width="15%;">Name</th>
        <th width="10%;">Mode No</th>
        <th width="15%;">Manufacturer</th>
        <th width="15%;">Depreication</th>
        <th width="5%;">Assets</th>
        <th width="40%;">Notes</th>
    </tr>
    </thead>
    
    <tbody>
    @foreach($models as $model)
        <tr>
            <td>{{ $model['name']}}</td>
            <td class="text-center">{{ $model['model_no']}}</td>
            <td class="text-left">{{ $model['manufacturer'] }}</td>
            <td class="text-left">{{ $model['depreciation'] }}</td>
            <td class="text-center">{{$model['assets']}}</td>
            <td class="text-left">{{$model['notes']}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Name</th>
            <th>Mode No</th>
            <th>Manufacturer</th>
            <th>Depreciation</th>
            <th>Assets</th>
            <th>Notes</th>
        </tr>
    </tfoot>
</table>
@endsection