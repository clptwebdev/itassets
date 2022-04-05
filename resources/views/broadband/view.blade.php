@extends('layouts.app')

@section('title', 'View Broadband')


@section('content')
    <x-wrappers.nav title="Broadband">
        @can('recycleBin', \App\Models\Broadband::class)
            <x-buttons.recycle :route="route('broadband.bin')" :count="\App\Models\Broadband::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Broadband::class)
            <x-buttons.add :route="route('broadbands.create')">Broadband</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Broadband::class)
            @if ($broadbands->count() == 1)
                <x-buttons.reports :route="route('broadband.showPdf', $broadbands[0]->id)"/>
            @elseif($broadbands->count() > 1)
                <x-form.layout class="d-inline-block" :action="route('broadband.pdf')">
                    <x-form.input type="hidden" name="broadband" :label="false" formAttributes="required"
                                  :value="json_encode($broadbands->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($broadbands->count() >1)
                <x-form.layout class="d-inline-block" action="/export/broadband">
                    <x-form.input type="hidden" name="broadband" :label="false" formAttributes="required"
                                  :value="json_encode($broadbands->pluck('id'))"/>
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
                    @can('create', \App\Models\Broadband::class)
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
        <p class="mt-5 mb-4">Below is Broadband belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the Broadband assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class='row justify-content-center m-4'>


            <div class="col-12 col-sm-8 col-lg-4 mb-4 mb-lg-0 order-3 order-lg-1">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-lilac text-uppercase mb-1">
                                    Previous Years Total Broadband
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    £<span id="total_cost"
                                           class="countup">{{number_format($previous_cost , 2, '.', ',')}}</span><br>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pound-sign fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-lg-4 mb-4 mb-lg-0 order-3 order-lg-1">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-lilac text-uppercase mb-1">
                                    Current Years Total Broadband
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    £<span id="total_cost"
                                           class="countup">{{number_format($current_cost , 2, '.', ',')}}</span><br>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pound-sign fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Renewal Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Package</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-2"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Renewal Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Package</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($locations as $location)
                            @if($location->singleBroadband()->first() != null)
                                <?php $broadband = $location->singleBroadband()->first();?>
                                <tr>
                                    <td class="text-center">{{$broadband->location->name ?? 'No Name'}}</td>
                                    <td class="text-center">
                                        £{{number_format($broadband->purchased_cost , 2, '.', ','  )}}</td>
                                    <td class="text-center">{{ \Illuminate\Support\Carbon::parse($broadband->purchased_date ?? \Carbon\Carbon::now())->format('d-M-Y') }}</td>
                                    <td class="text-center">{{$broadband->supplier->name ?? 'N/A'}}</td>
                                    <td class="text-center"><span>{{ \Illuminate\Support\Carbon::parse($broadband->renewal_date ?? \Carbon\Carbon::now())->format('d-M-Y')}}
                                    </span><br>
                                        @if($broadband)
                                            @if($broadband->isExpired())<small
                                                class='text-danger'>{{\Illuminate\Support\Carbon::parse($broadband->renewal_date ?? \Carbon\Carbon::now())->diffForHumans()}}</small>
                                            @else
                                                <small
                                                    class='text-success'>{{\Illuminate\Support\Carbon::parse($broadband->renewal_date ?? \Carbon\Carbon::now())->diffForHumans()}}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">{{$broadband->package ?? 'N/A'}}</td>
                                    <td class="text-center">
                                        @if($broadband)
                                            @if($broadband->isExpired())
                                                <p class='text-danger'>Expired</p>
                                            @else
                                                <p class='text-success'>Valid</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <x-wrappers.table-settings>
                                            @can('view', $broadband)
                                                <x-buttons.dropdown-item
                                                    :route="route('broadbands.show', $broadband->id)">
                                                    View
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('update', $broadband)
                                                <x-buttons.dropdown-item
                                                    :route=" route('broadbands.edit', $broadband->id)">
                                                    Edit
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('delete', $broadband)
                                                <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                               :id="'form'.$broadband->id"
                                                               :action="route('broadbands.destroy', $broadband->id)">
                                                    <x-buttons.dropdown-item :data="$broadband->id" class="deleteBtn">
                                                        Delete
                                                    </x-buttons.dropdown-item>
                                                </x-form.layout>
                                            @endcan
                                        </x-wrappers.table-settings>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if($broadbands->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Broadband Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$broadbands"/>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/broadband"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
@endsection
