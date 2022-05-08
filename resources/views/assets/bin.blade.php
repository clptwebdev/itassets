@extends('layouts.app')

@section('title', 'Recycle Bin | Assets')



@section('content')

    <x-wrappers.nav title="Assets | Recycle Bin">
        <x-buttons.return :route="route('assets.index')"> Assets</x-buttons.return>
        @can('generatePDF', \App\Models\Asset::class)
            @if($assets->count() >1)
                <x-form.layout class="d-inline-block" :action="route('assets.pdf')">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
        @endcan
        <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
           class="btn btn-sm  bg-yellow shadow-sm p-2 p-md-1"><i class="fas fa-question fa-sm text-dark-50 mr-lg-1"></i><span
                class="d-none d-lg-inline-block">Help</span></a>

    </x-wrappers.nav>

    <x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
                        different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->


        <div class="card shadow mb-4">
            <div class="card-body">
                <div>
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-9 col-md-3"><small>Item</small></th>
                            <th class="col-1 col-md-auto"><small>Location</small></th>
                            <th class="col-1 col-md-auto"><small>Tag</small></th>
                            <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
                            <th class="d-none d-xl-table-cell"><small>Date</small></th>
                            <th class="d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-right col-2"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Item</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{ $asset->name }}<br><small
                                        class="d-none d-md-inline-block">{{ $asset->model->name ?? 'No Model'}}</small>
                                </td>
                                <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                    @if(isset($asset->location->photo->path))
                                        <img src="{{ asset($asset->location->photo->path)}}" height="30px"
                                             alt="{{$asset->location->name}}"
                                             title="{{ $asset->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                            .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td>{{ $asset->asset_tag }}</td>
                                <td class="text-center d-none d-xl-table-cell">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                                <td class="d-none d-md-table-cell"
                                    data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                                <td class="text-center  d-none d-xl-table-cell">
                                    £{{ $asset->purchased_cost }}
                                    @if($asset->model)
                                        <br>
                                        @php
                                            $eol = Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                                            if($eol->isPast()){
                                                $dep = 0;
                                            }else{
                                                $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                                                $percent = 100 / $asset->model->depreciation->years;
                                                $percentage = floor($age)*$percent;
                                                $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                            }
                                        @endphp
                                        <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
                                </td>
                                <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Asset Options:</div>
                                            <a href="{{ route('assets.restore', $asset->id) }}" class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$asset->id}}"
                                                  action="{{ route('assets.remove', $asset->id) }}" method="POST">
                                                @csrf
                                                @can('delete', $asset)
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$asset->id}}">Delete</a>
                                                @endcan
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($assets) == 0)
                            <tr>
                                <td colspan="8" class="text-center text-muted">The Recycle Bin is empty!</td>
                            </tr>
                        @endif</tbody>
                    </table>
                    <x-paginate :model="$assets"/>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>This area can be minimised and will contain a little help on the page that the user is currently
                   on.</p>
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
