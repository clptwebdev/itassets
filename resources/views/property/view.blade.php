@extends('layouts.app')

@section('title', 'View Property')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
          integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css"
          integrity="sha512-9h7XRlUeUwcHUf9bNiWSTO9ovOWFELxTlViP801e5BbwNJ5ir9ua6L20tEroWZdm+HFBAWBLx2qH4l4QHHlRyg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endsection

@section('content')
    <x-wrappers.nav title="Property">
     {{--  @can('recycleBin', \App\Models\Asset::class)
            <x-buttons.recycle :route="route('assets.bin')" :count="\App\Models\Asset::onlyTrashed()->count()"/>
        @endcan --}}
        @can('create' , \App\Models\Property::class)
            <x-buttons.add :route="route('property.create')">Property</x-buttons.add>
        @endcan
           {{--
        @can('generatePDF', \App\Models\Asset::class)
            @if ($assets->count() == 1)
                <x-buttons.reports :route="route('asset.showPdf', $assets[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('assets.pdf')">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($assets->count() >1)
                <x-form.layout class="d-inline-block" action="/exportassets">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span></x-buttons.submit>
                </x-form.layout>
            @endif
            <div class="dropdown show d-inline">
                <a class="btn btn-sm btn-lilac dropdown-toggle p-2 p-md-1" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Options
                </a>
                <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="dropdownMenuLink">
                    @can('create', \App\Models\Asset::class)
                        <x-buttons.dropdown-item id="import">
                            Import
                        </x-buttons.dropdown-item>
                    @endcan
                    <x-buttons.dropdown-item form-requirements=" data-toggle='modal' data-target='#bulkDisposalModal'">
                        Dispose
                    </x-buttons.dropdown-item>
                    <x-buttons.dropdown-item form-requirements=" data-toggle='modal' data-target='#bulkTransferModal'">
                        Transfer
                    </x-buttons.dropdown-item>
                </div>
            </div>
        @endcan --}}
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
                        different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->
        
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-3 col-md-2"><small>Type</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Value</small></th>
                            <th class="text-center col-2 d-none d-xl-table-cell"><small>Depreciation (Years)</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-3 col-md-2"><small>Type</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Value</small></th>
                            <th class="text-center col-2 d-none d-xl-table-cell"><small>Depreciation (Years)</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                            @foreach($properties as $property)
                            <tr>
                                <td class="text-left">{{$property->name}}</td>
                                <td class="text-left">
                                    @switch($property->type)
                                        @case(1)
                                            {{'Freehold Land'}}
                                            @break
                                        @case(2)
                                            {{'Freehold Building'}}
                                            @break
                                        @case(3)
                                            {{'Leasehold Land'}}
                                            @break
                                        @case(4)
                                            {{'Leasehold Building'}}
                                            @break
                                        @default
                                            {{'Unknown'}}
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if($property->location()->exists())
                                        @if($property->location->photo()->exists())
                                            <img src="{{ asset($property->location->photo->path)}}" height="30px"
                                                 alt="{{$property->location->name}}"
                                                 title="{{ $property->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($property->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">£{{number_format($property->value, 2, '.', ',')}}</td>
                                <td class="text-center">{{$property->depreciation}} Years</td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $property)
                                            <x-buttons.dropdown-item :route="route('property.show', $property->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $property)
                                                <x-buttons.dropdown-item :route=" route('property.edit', $property->id)">
                                                    Edit
                                                </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $property)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$property->id" :action="route('property.destroy', $property->id)">
                                                <x-buttons.dropdown-item :data="$property->id" class="deleteBtn" >
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                            @endforeach
                            @if($properties->count() == 0)
                            <tr>
                                <td colspan="6" class="text-center">No Assets Returned</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>{{-- 
                    <x-paginate :model="$assets"/> --}}
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for the
                   Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection
@section('modals')

@endsection

@section('js')


@endsection
