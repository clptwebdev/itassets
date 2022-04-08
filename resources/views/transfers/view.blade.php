@extends('layouts.app')

@section('title', 'Asset Transfers')


@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title}}</h1>
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

    <x-handlers.alerts/>


    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
                        different options and locations can created, updated, deleted and filtered</p>


        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-2 text-center"><small>Item</small></th>
                            <th class="col-2 text-center"><small>Location From</small></th>
                            <th class="col-2 text-center"><small>Location To</small></th>
                            <th class="col-2 text-center"><small>Cost</small></th>
                            <th class="col-1 text-center"><small>Requested by</small></th>
                            <th class="col-1 text-center"><small>Requested On</small></th>
                            <th class="col-1 text-center"><small>Approved By</small></th>
                            <th class="col-1 text-center"><small>Approved On</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Item</small></th>
                            <th><small>Location From</small></th>
                            <th><small>Location To</small></th>
                            <th><small>Cost</small></th>
                            <th class="text-center"><small>Requested by</small></th>
                            <th class="text-center"><small>Requested On</small></th>
                            <th class="text-center"><small>Approved By</small></th>
                            <th class="text-center"><small>Approved On</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($transfers as $transfer)
                            @php
                                $m = "\\App\\Models\\".ucfirst($transfer->model_type);
                                $model = $m::find($transfer->model_id);
                            @endphp
                            <tr>
                                <td>{{ $model->name ?? $model->model->name  ?? 'Unknown'}}</td>
                                <td class="text-center text-sm-left">
                                    @if(isset($transfer->from->photo->path))
                                        <img src="{{ asset($transfer->from->photo->path)}}" height="30px"
                                             alt="{{$transfer->from->name}}" title="{{ $transfer->from->name }}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($transfer->from->icon ?? '#666').'" data-bs-toggle="tooltip" data-bs-placement="top" title="">'
                                            .strtoupper(substr($transfer->from->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                    <small>{{$transfer->from->name}}</small>
                                </td>
                                <td class="text-center text-sm-left">
                                    @if(isset($transfer->from->photo->path))
                                        <img src="{{ asset($transfer->to->photo->path)}}" height="30px"
                                             alt="{{$transfer->to->name}}" title="{{ $transfer->to->name }}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($transfer->to->icon ?? '#666').'" data-bs-toggle="tooltip" data-bs-placement="top" title="">'
                                            .strtoupper(substr($transfer->to->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                    <small>{{$transfer->to->name}}</small>
                                </td>
                                <td class="text-center">£{{ $transfer->value}}<br><small>Original Cost:
                                                                                         £{{ $model->purchased_cost ?? ' - Unknown' }}
                                </td>
                                <td class="text-center">
                                    @if($transfer->requested->photo()->exists())
                                        <img class="img-profile rounded-circle"
                                             src="{{ asset($transfer->requested->photo->path) ?? asset('images/profile.png') }}"
                                             width="50px" title="{{ $transfer->requested->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}"
                                             width="50px" title="{{ $transfer->requested->name ?? 'Unknown' }}">
                                    @endif
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($transfer->created_at)->format("d/m/Y") }}</td>
                                <td class="text-center">
                                    @if($transfer->approved->photo()->exists())
                                        <img class="img-profile rounded-circle"
                                             src="{{ asset($transfer->approved->photo->path) ?? asset('images/profile.png') }}"
                                             width="50px" title="{{ $transfer->approved->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}"
                                             width="50px" title="{{ $transfer->approved->name ?? 'Unknown' }}">
                                    @endif
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($transfer->updated_at)->format("d/m/Y") }}</td>

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
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for a the
                   Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection

@section('modals')


@endsection

@section('js')

@endsection
