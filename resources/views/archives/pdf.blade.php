@extends('layouts.pdf-reports')

@section('title', 'Archives Report')

@section('page', 'Archives')

@section('user', $user->name)

@section('content')
    <table class="table" width="100%">
        <thead>
        <tr>
            <th class="col-9 col-md-2"><small>Item</small></th>
            <th class="d-none d-xl-table-cell"><small>Model</small></th>
            <th class="col-1 col-md-auto"><small>Location</small></th>
            <th class="col-1 col-md-auto"><small>Tag</small></th>
            <th class="d-none d-xl-table-cell"><small>Date</small></th>
            <th class="d-none d-xl-table-cell"><small>Cost</small></th>
            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
            <th class="col-auto d-none d-xl-table-cell"><small>Requested By</small></th>
            <th class="col-auto text-center d-none d-md-table-cell"><small>Approved By</small></th>
        </tr>
        </thead>

        <tbody>
        @foreach($archives as $archive)
            <tr>
                <td>{{$archive['name']}}<br><small class="d-none d-md-inline-block">{{ $archive['serial_no']}}</small>
                </td>
                <td class="text-center d-none d-xl-table-cell">{{ $archive['model'] }}<br>
                </td>
                <td class="text-center text-md-left" data-sort="{{ $archive['location']}}">
                    {{$archive['location']}}
                </td>
                <td>{{ $archive['asset_tag']}}</td>
                <td class="d-none d-md-table-cell">
                    {{ $archive['purchased_date']}}<br>
                    <small class="text-danger">Disposed
                                               on:{{ $archive['created_at']}}</small>
                </td>
                <td class="text-center  d-none d-xl-table-cell">
                    £{{ $archive['purchased_cost'] }}<br><small>Value at Disposal -
                                                                £{{ $archive['archived_cost']}}</small>
                </td>
                <td class="text-center d-none d-xl-table-cell">{{$archive['supplier']}}<br><small>Order
                                                                                                  No: {{ $archive['order_no']}}</small>
                </td>
                <td class="text-center">
                    {{ $archive['requested']}}
                    <small>{{ $archive['created_at']}}</small>
                </td>
                <td class="text-center">
                    {{ $archive['approved'] }}
                    <small>{{$archive['updated_at']}}</small>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th class="col-9 col-md-2"><small>Item</small></th>
            <th class="d-none d-xl-table-cell"><small>Model</small></th>
            <th class="col-1 col-md-auto"><small>Location</small></th>
            <th class="col-1 col-md-auto"><small>Tag</small></th>
            <th class="d-none d-xl-table-cell"><small>Date</small></th>
            <th class="d-none d-xl-table-cell"><small>Cost</small></th>
            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
            <th class="col-auto d-none d-xl-table-cell"><small>Requested By</small></th>
            <th class="col-auto text-center d-none d-md-table-cell"><small>Approved By</small></th>
        </tr>
        </tfoot>
    </table>
@endsection
