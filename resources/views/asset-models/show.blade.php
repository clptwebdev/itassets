@extends('layouts.app')

@section('title', 'View '.$assetModel->name)

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Asset Model</h1>
        <div>
            <a href="{{ route('asset-models.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm">
                <i class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('delete', $assetModel)
                <form class="d-inline-block" id="form{{$assetModel->id}}"
                      action="{{ route('asset-models.destroy', $assetModel->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="d-none d-sm-inline-block btn btn-sm btn-danger deleteBtn" href="#"
                       data-id="{{$assetModel->id}}"><i class="fas fa-trash-alt fa-sm text-white-50"></i>Delete</a>
                </form>

            @endcan
            @can('update', $assetModel)
                <a href="{{ route('asset-models.edit', $assetModel->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
            @endcan
            @can('view', $assetModel)
                <a href="{{ route('asset-model.showPdf', $assetModel->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            @endcan
        </div>
    </div>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Information regarding Asset Model: {{ $assetModel->name }}, the assets that are currently
                        assigned to the it and any other requested information.</p>


        <div class="card shadow h-100 pb-2 m-2" style="border-left: 0.25rem solid 666;">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Asset Model Information</h6>
            </div>
            <div class="card-body">
                <div class="row no-gutters">
                    <div class="col-12 col-sm-4 col-md-3 col-xl-2 p-2">
                        <div class="border border-gray-100">
                            @if($assetModel->photo()->exists())
                                <img src="{{ asset($assetModel->photo->path) }}" width="100%"
                                     alt="{{ $assetModel->name }}" title="{{ $assetModel->name }}">
                            @else
                                <img src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                     alt="{{ $assetModel->name }}" title="{{ $assetModel->name }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-8 col-md-9 col-xl-10"
                    ">
                    <div class="mb-1">
                        <table class="table table-striped">
                            <tr>
                                <td width="20%">Name</td>
                                <td width="80%">{{$assetModel->name}}</td>
                            </tr>
                            <tr>
                                <td>Manufacturer</td>
                                <td>{{$assetModel->manufacturer->name}}</td>
                            </tr>
                            <tr>
                                <td>Model N<sup>o</sup></td>
                                <td>{{$assetModel->model_no}}</td>
                            </tr>
                            <tr>
                                <td>Depreciation Model</td>
                                <td>{{$assetModel->depreciation->name}}</td>
                            </tr>
                            <tr>
                                <td>EOL</td>
                                <td>{{$assetModel->eol}} Months</td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>{{$assetModel->notes}}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="card shadow h-100 pb-2 m-2" style="border-left: 0.25rem solid 666;">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Assigned Assets</h6>
            </div>
            <div class="card-body">
                <table id="assetsTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-9 col-md-2"><small>Item</small></th>
                        <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                        <th class="col-1 col-md-auto"><small>Tag</small></th>
                        <th class="d-none d-xl-table-cell"><small>Date</small></th>
                        <th class="d-none d-xl-table-cell text-center"><small>Cost</small></th>
                        <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                        <th class="col-auto d-none d-xl-table-cell text-center"><small>Warranty (M)</small></th>
                        <th class="col-auto text-center d-none d-md-table-cell"><small>Audit Due</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th><small>Item</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th><small>Tag</small></th>
                        <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                        <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                        <th class=" d-none d-xl-table-cell text-center"><small>Supplier</small></th>
                        <th class=" d-none d-xl-table-cell text-center"><small>Warranty (M)</small></th>
                        <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($assetModel->assets as $asset)
                        <tr>
                            <td>{{ $assetModel->name ?? 'No Model'}}<br><small
                                    class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                            <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                @if($asset->location->photo()->exists())
                                    <img src="{{ asset($asset->location->photo->path)}}" height="30px"
                                         alt="{{$asset->location->name}}"
                                         title="{{ $asset->location->name ?? 'Unnassigned'}}"/>
                                @else
                                    {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                        .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif
                            </td>
                            <td>{{ $asset->asset_tag }}</td>
                            <td class="d-none d-md-table-cell"
                                data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                            <td class="text-center  d-none d-xl-table-cell">
                                £{{ $asset->purchased_cost }}

                            </td>
                            <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                            @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                            <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                {{ $asset->warranty }} Months

                                <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                    Remaining</small>
                            </td>
                            <td class="text-center d-none d-xl-table-cell"
                                data-sort="{{ strtotime($asset->audit_date)}}">
                                @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                                    <span
                                        class="text-danger">{{\Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                    <br><small>Audit Overdue</small>
                                @else
                                    <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                                    @switch(true)
                                        @case($age < 31)
                                        <span
                                            class="text-warning">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                        <br><small>Audit Due Soon</small>
                                        @break
                                        @default
                                        <span
                                            class="text-secondary">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                        <br><small>Audit due in {{floor($age)}} days</small>
                                    @endswitch
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                       id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Asset Options:</div>
                                        <a href="{{ route('assets.show', $asset->id) }}" class="dropdown-item">View</a>
                                        @can('edit', $asset)
                                            <a href="{{ route('assets.edit', $asset->id) }}"
                                               class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $asset)
                                            <form id="form{{$asset->id}}"
                                                  action="{{ route('assets.destroy', $asset->id) }}" method="POST"
                                                  class="d-block p-0 m-0">
                                                @csrf
                                                @method('DELETE')
                                                <a class="deleteBtn dropdown-item" href="#" data-id="{{$asset->id}}">Delete</a>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection

@section('modals')

    <x-modals.delete :archive="true"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>

@endsection
