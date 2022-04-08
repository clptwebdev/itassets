@extends('layouts.app')

@section('title', 'View Plant and Machinery')


@section('content')
    <x-wrappers.nav title="View Plant and Machinery">
        @can('recycleBin', \App\Models\Machinery::class)
            <x-buttons.recycle :route="route('machinery.bin')" :count="\App\Models\Machinery::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Machinery::class)
            <x-buttons.add :route="route('machineries.create')">Machinery</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Machinery::class)
            @if ($machineries->count() == 1)
                <x-buttons.reports :route="route('machinery.showPdf', $machineries[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('machinery.pdf')">
                    <x-form.input type="hidden" name="machinery" :label="false" formAttributes="required"
                                  :value="json_encode($machineries->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf" class="btn-blue">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($machineries->count() >1)
                <x-form.layout class="d-inline-block" action="/export/machinery">
                    <x-form.input type="hidden" name="machinery" :label="false" formAttributes="required"
                                  :value="json_encode($machineries->pluck('id'))"/>
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
                    @can('create', \App\Models\Machinery::class)
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
        <p class="mt-5 mb-4">Below is machinery belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the machinery assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>

        @php

            $limit = auth()->user()->location_property()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_property()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

            if(session()->has('machinery_min') && session()->has('machinery_max')){
            $start_value = session('machinery_min');
            $end_value = session('machinery_max');
            }else{
                $start_value = $floor;
                $end_value = $limit;
            }
        @endphp

        <x-filters.navigation model="Machinery" relations="machinery" table="machineries"/>
        <x-filters.filter model="Machinery" relations="machinery" table="machineries" :locations="$locations"/>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <table id="assetsTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-2 col-md-2"><small>Name</small></th>
                        <th class="col-2 col-md-2 text-center"><small>Purchase Cost</small></th>
                        <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                        <th class="text-center col-3 d-none d-xl-table-cell"><small>Supplier</small></th>
                        <th class="col-2 col-md-auto text-center"><small>Location</small></th>
                        <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                        </th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th class="col-4 col-md-2"><small>Name</small></th>
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
                    @foreach($machineries as $machinery)
                        <tr>
                            <td class="text-left">{{$machinery->name}}</td>
                            <td class="text-center">£{{number_format($machinery->purchased_cost, 2, '.', ',')}}</td>
                            <td class="text-center">{{ \Illuminate\Support\Carbon::parse($machinery->purchased_date)->format('d-M-Y')}}</td>
                            <td class="text-center">{{$machinery->supplier->name}}</td>
                            <td class="text-center">{{$machinery->location->name}}</td>
                            <td class="text-center">
                                £{{number_format($machinery->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}
                                <br><small>{{$machinery->depreciation}} Years</small></td>
                            <td class="text-right">
                                <x-wrappers.table-settings>
                                    @can('view', $machinery)
                                        <x-buttons.dropdown-item :route="route('machineries.show', $machinery->id)">
                                            View
                                        </x-buttons.dropdown-item>
                                    @endcan
                                    @can('update', $machinery)
                                        <x-buttons.dropdown-item :route=" route('machineries.edit', $machinery->id)">
                                            Edit
                                        </x-buttons.dropdown-item>
                                    @endcan
                                    @can('delete', $machinery)
                                        <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                       :id="'form'.$machinery->id"
                                                       :action="route('machineries.destroy', $machinery->id)">
                                            <x-buttons.dropdown-item :data="$machinery->id" class="deleteBtn">
                                                Delete
                                            </x-buttons.dropdown-item>
                                        </x-form.layout>
                                    @endcan
                                </x-wrappers.table-settings>
                            </td>
                        </tr>
                    @endforeach
                    @if($machineries->count() == 0)
                        <tr>
                            <td colspan="9" class="text-center">No Plant and Machinery Returned</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <x-paginate :model="$machineries"/>
            </div>
        </div>
    </section>
@endsection

@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/machinery"/>
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
