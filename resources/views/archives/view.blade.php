@extends('layouts.app')

@section('title', 'Archives')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
    
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Archives</h1>
        <div>
            {{-- @can('recycleBin', \App\Models\Asset::class)
                <a href="{{ route('assets.bin')}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                        class="fas fa-trash-alt fa-sm text-white-50"></i> Recycle Bin
                    ({{ \App\Models\Asset::onlyTrashed()->count()}})</a>
            @endcan
            @can('create', \App\Models\Asset::class)
                <a href="{{ route('assets.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="fas fa-plus fa-sm text-dark-50"></i> Add New Asset(s)</a>
            @endcan
            @can('generatePDF', \App\Models\Asset::class)
            @if($assets->count() != 0)
                @if ($assets->count() == 1)
                <a href="{{ route('asset.showPdf', $assets[0]->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</a>
                @else
                <form class="d-inline-block" action="{{ route('assets.pdf')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ json_encode($assets->pluck('id'))}}" name="assets"/>
                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</button>
                </form>
                @endif
            @endif
            @endcan
            @can('create', \App\Models\Asset::class)
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-download fa-sm text-dark-50 fa-text-width"></i> Import</a>
            @endcan
            @if($assets->count() > 1)
                @can('generatePDF', \App\Models\Asset::class)
                <form class="d-inline-block" action="/exportassets" method="POST">
                    @csrf
                    <input type="hidden" value="{{ json_encode($assets->pluck('id'))}}" name="assets"/>
                <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                        class="fas fa-download fa-sm text-dark-50"></i> Export</button>
                </form>
                @endcan
            @endif --}}
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
            different options and locations can created, updated, deleted and filtered</p>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
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
                        <tfoot>
                        <tr>
                            <th><small>Item</small></th>
                            <th class="d-none d-xl-table-cell"><small>Model</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Warranty (M)</small></th>
                            <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($archives as $archive)
                            <tr>
                                <td>{{$archive->name}}<br><small
                                        class="d-none d-md-inline-block">{{ $archive->serial_no ?? 'N/A'}}</small></td>
                                <td class="text-center d-none d-xl-table-cell">{{ $asset->asset_model ?? 'N/A' }}<br></td>
                                <td class="text-center text-md-left" data-sort="{{ $archive->location->name ?? 'Unnassigned'}}">
                                    @if(isset($archive->location->photo->path))
                                        <img src="{{ asset($archive->location->photo->path)}}" height="30px" alt="{{$archive->location->name}}" title="{{ $archive->location->name }}<br>{{ $asset->room ?? 'Unknown'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'" data-toggle="tooltip" data-placement="top" title="">'
                                            .strtoupper(substr($archive->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                    <small class="d-none d-md-inline-block">{{$archive->location->name}}</small>
                                </td>
                                <td>{{ $archive->asset_tag ?? 'N/A'}}</td>
                                <td class="d-none d-md-table-cell"data-sort="{{ strtotime($archive->purchased_date)}}">
                                    {{ \Carbon\Carbon::parse($archive->purchased_date)->format('d/m/Y')}}
                                </td>
                                <td class="text-center  d-none d-xl-table-cell">
                                    £{{ $archive->purchased_cost }}<br><small>Value at Disposal - £{{ $archive->archived_cost}}</small>
                                </td>
                                <td class="text-center d-none d-xl-table-cell">{{$archive->supplier->name ?? "N/A"}}<br><small>Order No: {{ $archive->order_no ?? 'N/A'}}</small></td>
                                <td class="text-center">
                                    @if($archive->requested->photo()->exists())
                                    <img class="img-profile rounded-circle"
                                        src="{{ asset($archive->requested->photo->path) ?? asset('images/profile.png') }}" width="50px" title="{{ $archive->requested->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}" width="50px" title="{{ $archive->requested->name ?? 'Unknown' }}">
                                    @endif
                                    <small>{{ \Carbon\Carbon::parse($archive->created_at)->format("d/m/Y") }}</small>
                                </td>
                                <td class="text-center">
                                    @if($archive->approved->photo()->exists())
                                    <img class="img-profile rounded-circle"
                                        src="{{ asset($archive->approved->photo->path) ?? asset('images/profile.png') }}" width="50px" title="{{ $archive->approved->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}" width="50px" title="{{ $archive->approved->name ?? 'Unknown' }}">
                                    @endif
                                    <small>{{ \Carbon\Carbon::parse($archive->updated_at)->format("d/m/Y") }}</small>
                                </td>
                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for a the Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection
<?php session()->flash('import-error', 'Select a file to be uploaded before continuing!');?>

@section('modals')
    
@endsection

@section('js')

    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        

        $(document).ready(function () {
            $('#assetsTable').DataTable({
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "targets": [9],
                    "orderable": false
                }],
                "order": [[1, "asc"]],
            });
        });
    </script>
@endsection
