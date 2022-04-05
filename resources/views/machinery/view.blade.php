@extends('layouts.app')

@section('title', 'View Vehicles')


@section('content')
    <x-wrappers.nav title="Vehicles">
        @can('recycleBin', \App\Models\Vehicle::class)
            <x-buttons.recycle :route="route('vehicle.bin')" :count="\App\Models\Vehicle::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Vehicle::class)
            <x-buttons.add :route="route('vehicles.create')">Software</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Vehicle::class)
            @if ($vehicles->count() == 1)
                <x-buttons.reports :route="route('vehicle.showPdf', $vehicles[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('vehicle.pdf')">
                    <x-form.input type="hidden" name="vehicle" :label="false" formAttributes="required"
                                  :value="json_encode($vehicles->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($vehicles->count() >1)
                <x-form.layout class="d-inline-block" action="/export/vehicle">
                    <x-form.input type="hidden" name="vehicle" :label="false" formAttributes="required"
                                  :value="json_encode($vehicles->pluck('id'))"/>
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
                    @can('create', \App\Models\Vehicle::class)
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
        <p class="mt-5 mb-4">Below is Vehicles belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the Vehicles assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>

        @php

            $limit = auth()->user()->location_property()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_property()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

        if(session()->has('property_amount')){
            $amount = str_replace('£', '', session('property_amount'));
            $amount = explode(' - ', $amount);
            $start_value = intval($amount[0]);
            $end_value = intval($amount[1]);
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
        @endphp

        <x-filters.navigation model="Vehicle" relations="vehicle" table="vehicles"/>
        <x-filters.filter model="Vehicle" relations="vehicle" table="vehicles" :locations="$locations"/>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-4 col-md-2"><small>Registration</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-4 col-md-2"><small>Registration</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($vehicles as $vehicle)
                            <tr>
                                <td class="text-left">{{$vehicle->name}}</td>
                                <td class="text-left">{{$vehicle->registration ?? 'N/A'}}</td>
                                <td class="text-center">£{{number_format($vehicle->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($vehicle->purchased_date)->format('d-M-Y')}}</td>
                                <td class="text-center">{{$vehicle->supplier->name}}</td>
                                <td class="text-center">{{$vehicle->location->name}}</td>
                                <td class="text-center">
                                    £{{number_format($vehicle->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}
                                    <br><small>{{$vehicle->depreciation}} Years</small></td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $vehicle)
                                            <x-buttons.dropdown-item :route="route('vehicles.show', $vehicle->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $vehicle)
                                            <x-buttons.dropdown-item :route=" route('vehicles.edit', $vehicle->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $vehicle)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                           :id="'form'.$vehicle->id"
                                                           :action="route('vehicles.destroy', $vehicle->id)">
                                                <x-buttons.dropdown-item :data="$vehicle->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($vehicles->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Vehicles Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$vehicles"/>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/vehicle"/>
@endsection

@section('js')
    <script src="{{asset('js/filter.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
    <script>
        let sliderMin = document.querySelector('#customRange1');
        let sliderMax = document.querySelector('#customRange2');
        let sliderMinValue = document.querySelector('#minRange');
        let sliderMaxValue = document.querySelector('#maxRange');

        //setting slider ranges
        sliderMin.setAttribute('min', {{ floor($start_value)}});
        sliderMin.setAttribute('max', {{ round($end_value)}});
        sliderMax.setAttribute('min', {{ floor($start_value)}});
        sliderMax.setAttribute('max', {{ round($end_value)}});
        sliderMax.value = {{ round($end_value)}};
        sliderMin.value = {{ floor($start_value)}};

        sliderMinValue.innerHTML = {{ floor($start_value)}};
        sliderMaxValue.innerHTML = {{ round($end_value)}};

        sliderMin.addEventListener('input', function () {
            sliderMinValue.innerHTML = sliderMin.value;
            sliderMaxValue.innerHTML = sliderMax.value;

        });
        sliderMax.addEventListener('input', function () {
            sliderMaxValue.innerHTML = sliderMax.value;
            sliderMinValue.innerHTML = sliderMin.value;
            sliderMin.setAttribute('max', sliderMax.value);


        });
    </script>
@endsection
