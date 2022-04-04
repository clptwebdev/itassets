@extends('layouts.app')

@section('title', 'View Licenses')


@section('content')
    <x-wrappers.nav title="Licenses">
        @can('recycleBin', \App\Models\License::class)
            <x-buttons.recycle :route="route('license.bin')" :count="\App\Models\License::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\License::class)
            <x-buttons.add :route="route('licenses.create')">License</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\License::class)
            @if ($licenses->count() == 1)
                <x-buttons.reports :route="route('license.showPdf', $licenses[0]->id)"/>
            @elseif($licenses->count() > 1)
                <x-form.layout class="d-inline-block" :action="route('license.pdf')">
                    <x-form.input type="hidden" name="License" :label="false" formAttributes="required"
                                  :value="json_encode($licenses->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($licenses->count() >1)
                <x-form.layout class="d-inline-block" action="/export/license">
                    <x-form.input type="hidden" name="license" :label="false" formAttributes="required"
                                  :value="json_encode($licenses->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span>
                    </x-buttons.submit>
                </x-form.layout>
            @endif
            <div class="dropdown d-inline-block">
                <a class="btn btn-sm btn-lilac dropdown-bs-toggle p-2 p-md-1" href="#" role="button"
                   id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Options
                </a>
                <div class="dropdown-menu dropdown-menu-end text-end" aria-labelledby="dropdownMenuLink">
                    @can('create', \App\Models\License::class)
                        <x-buttons.dropdown-item id="import">
                            Import
                        </x-buttons.dropdown-item>
                    @endcan
                </div>
            </div>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are licenses belonging to the Central Learning Partnership Trust.If You require
                             access to see
                             the licenses assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Expiry</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Contact</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Expiry</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Contact</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($licenses as $license)
                            <tr>
                                <td class="text-left">{{$license->name ?? 'No License Name'}}</td>
                                <td class="text-center">{{$license->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center">{{$license->location->name}}</td>
                                <td class="text-center">£{{number_format($license->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center"><span>{{ \Illuminate\Support\Carbon::parse($license->expiry)->format('d-M-Y')}}
                                    </span><br>@if($license->isExpired())<small
                                        class='text-danger'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                    @else
                                        <small
                                            class='text-success'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                    @endif
                                </td>
                                <td class="text-center"
                                    href='mailto:{{$license->contact}}'>{{$license->contact ?? 'No Contact Email'}}</td>
                                <td class="text-center">
                                    @if($license->isExpired())
                                        <p class='text-danger'>Expired</p>
                                    @else
                                        <p class='text-success'>Valid</p>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $license)
                                            <x-buttons.dropdown-item :route="route('licenses.show', $license->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $license)
                                            <x-buttons.dropdown-item :route=" route('licenses.edit', $license->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $license)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                           :id="'form'.$license->id"
                                                           :action="route('licenses.destroy', $license->id)">
                                                <x-buttons.dropdown-item :data="$license->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($licenses->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Licenses Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$licenses"/>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/license"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
@endsection
