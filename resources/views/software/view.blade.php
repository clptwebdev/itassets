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

            $limit = auth()->user()->location_software()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_software()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

        if(session()->has('vehicle_min') && session()->has('vehicle_max')){
            $start_value = session('vehicle_min');
            $end_value = session('vehicle_max');
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
        @endphp

        

        <x-filters.navigation model="Software" relations="software" table="softwares"/>
        <x-filters.filter model="Software" relations="software" table="softwares" :locations="$locations"/>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-2"><small>Name</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-2"><small>Supplier</small></th>
                            <th class="text-center col-1"><small>Manufacturer</small></th>
                            <th class="text-center col-1"><small>Purchase Date</small></th>
                            <th class="col-1 text-center"><small>Purchase Cost</small></th>
                            <th class="col-1 text-center"><small>Current Value</small></th>
                            <th class="text-center col-1"><small>Depreciation (Years)</small></th>
                            <th class="text-center col-1"><small>Warranty</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Supplier</small></th>
                            <th class="text-center"><small>Manufacturer</small></th>
                            <th class="text-center"><small>Purchase Date</small></th>
                            <th class="text-center"><small>Purchase Cost</small></th>
                            <th class="text-center"><small>Current Value</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-center"><small>Depreciation (Years)</small></th>
                            <th class="text-end"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($softwares as $software)
                            <tr>
                                <td class="text-start">{{$software->name}}</td>
                                <td class="text-center">
                                    @if(isset($software->location->photo->path))
                                        <img src="{{ asset($software->location->photo->path)}}" height="30px"
                                                alt="{{$software->location->name}}"
                                                title="{{ $software->location->name }}<br>{{ $software->room ?? 'Unknown'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($software->location->icon ?? '#666').'" data-bs-toggle="tooltip" data-bs-placement="top" title="">'
                                            .strtoupper(substr($software->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{$software->supplier->name ?? 'N/A'}}
                                    @if($software->order_no) {!! '<br><small>'.$software->order_no.'</small>' !!}@endif
                                </td>
                                <td class="text-center">{{$software->manufacturer->name ?? 'N/A'}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($software->purchased_date)->format('d-M-Y')}}</td>
                                <td class="text-center">£{{number_format($software->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">£{{number_format($software->depreciation_value(), 2, '.', ',')}}</td>
                                <td class="text-center">{{$software->depreciation}} Years</td>
                                <td class="text-center">{{$software->warranty.' months' ?? 'None'}}</td>
                                <td class="text-end">
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
