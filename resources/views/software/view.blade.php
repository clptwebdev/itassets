@extends('layouts.app')

@section('title', 'View Software')


@section('content')
    <x-wrappers.nav title="Software">
        @can('recycleBin', \App\Models\Software::class)
            <x-buttons.recycle :route="route('software.bin')" :count="\App\Models\Software::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Software::class)
            <x-buttons.add :route="route('softwares.create')">Software</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Software::class)
            @if ($softwares->count() == 1)
                <x-buttons.reports :route="route('software.showPdf', $softwares[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('software.pdf')">
                    <x-form.input type="hidden" name="software" :label="false" formAttributes="required"
                                  :value="json_encode($softwares->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($softwares->count() >1)
                <x-form.layout class="d-inline-block" action="/export/software">
                    <x-form.input type="hidden" name="software" :label="false" formAttributes="required"
                                  :value="json_encode($softwares->pluck('id'))"/>
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
                    @can('create', \App\Models\Software::class)
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
        <p class="mt-5 mb-4">Below is Software belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the Software assigned to the different locations. If you think you have the incorrect
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

        <x-filters.navigation model="Software" relations="software" table="Software"/>
        <x-filters.filter model="Software" relations="software" table="Software" :locations="$locations"/>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
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
                        @foreach($softwares as $software)
                            <tr>
                                <td class="text-left">{{$software->name}}</td>
                                <td class="text-center">£{{number_format($software->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($software->purchased_date)->format('d-M-Y')}}</td>
                                <td class="text-center">{{$software->supplier->name}}</td>
                                <td class="text-center">{{$software->location->name}}</td>
                                <td class="text-center">
                                    £{{number_format($software->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}
                                    <br><small>{{$software->depreciation}} Years</small></td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $software, \App\Models\Software::class)
                                            <x-buttons.dropdown-item :route="route('softwares.show', $software->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $software)
                                            <x-buttons.dropdown-item :route=" route('softwares.edit', $software->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $software)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                           :id="'form'.$software->id"
                                                           :action="route('softwares.destroy', $software->id)">
                                                <x-buttons.dropdown-item :data="$software->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($softwares->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Software Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$softwares"/>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/software"/>
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
