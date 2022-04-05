@extends('layouts.app')

@section('title', 'View Furniture, Fixtures and Equipment (FFE)')


@section('content')
    <x-wrappers.nav title="Furniture, Fixtures and Equipment (FFE)">
        @can('recycleBin', \App\Models\FFE::class)
            <x-buttons.recycle :route="route('ffe.bin')" :count="\App\Models\FFE::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\FFE::class)
            <x-buttons.add :route="route('ffes.create')">FFE</x-buttons.add>
        @endcan
        @can('viewAll', \App\Models\FFE::class)
            @if ($ffes->count() == 1)
                <x-buttons.reports :route="route('ffes.showPdf', $ffes[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('ffes.pdf')">
                    <x-form.input type="hidden" name="ffes" :label="false" formAttributes="required"
                                :value="json_encode($ffes->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($ffes->count() > 1)
                <x-form.layout class="d-inline-block" action="/export/aucs">
                    <x-form.input type="hidden" name="ffes" :label="false" formAttributes="required"
                                :value="json_encode($ffes->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span></x-buttons.submit>
                </x-form.layout>
            @endif
            <div class="dropdown d-inline-block">
                <a class="btn btn-sm btn-lilac dropdown-toggle p-2 p-md-1" href="#" role="button" id="dropdownMenuLink"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Options
                </a>
                <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="dropdownMenuLink">
                    @can('create', \App\Models\FFE::class)
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
        <p class="mt-5 mb-4">Below is the Furniture, Fixtures and Equipment(FFE) that is currently located within the
                             different schools in the Central Learning Partnership Trust. You require access to see
                             the FFE assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>

        @php
        
        $limit = auth()->user()->location_ffe()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_ffe()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

        if(session()->has('ffe_min') && session()->has('ffe_max')){
            $start_value = session('ffe_min');
            $end_value = session('ffe_max');
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
        @endphp

        {{-- If there are no Collections return there is not need to display the filter, unless its the filter thats return 0 results --}}
        @if($ffes->count() !== 0 || session('ffe_filter') === true)
            <x-filters.navigation model="FFE" relations="ffe" table="f_f_e_s"/>
            <x-filters.filter model="FFE" relations="ffe" table="f_f_e_s" :locations="$locations" :statuses="$statuses"
            :categories="$categories" />
        @endif

    <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="" id="table">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-2 col-md-1 text-center"><small>Location</small></th>
                            <th class="text-center col-5 col-md-2 d-none d-sm-table-cell"><small>Manufacturers</small>
                            </th>
                            <th class="d-none d-xl-table-cell"><small>Purchased Date</small></th>
                            <th class="d-none d-xl-table-cell"><small>Purchased Cost</small></th>
                            <th class="d-none d-xl-table-cell col-2"><small>Supplier</small></th>
                            <th class="text-cente d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-center d-none d-xl-table-cell"><small>Warranty</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center d-none d-sm-table-cell"><small>Manufacturers</small></th>
                            <th class="d-none d-xl-table-cell"><small>Purchased Date</small></th>
                            <th class="d-none d-xl-table-cell"><small>Purchased Cost</small></th>
                            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-center d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-center d-none d-xl-table-cell"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($ffes as $ffe)
                            <tr>
                                <td>{{$ffe->name}}
                                    @if($ffe->serial_no != 0)
                                        <br>
                                        <small>{{$ffe->serial_no}}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ffe->location()->exists())
                                        @if($ffe->location->photo()->exists())
                                            <img src="{{ asset($ffe->location->photo->path)}}" height="30px"
                                                 alt="{{$ffe->location->name}}"
                                                 title="{{ $ffe->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($ffe->location->icon ?? '#666').'">'
                                                .strtoupper(substr($ffe->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center ">{{$ffe->manufacturer->name ?? "N/A"}}</td>
                                <td class="d-none d-xl-table-cell"
                                    data-sort="{{ strtotime($ffe->purchased_date)}}">{{\Carbon\Carbon::parse($ffe->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center d-none d-xl-table-cell">
                                    £{{$ffe->purchased_cost}} @if($ffe->donated == 1) <span
                                        class="text-sm">*Donated</span> @endif
                                        <br><small class="text-coral">(*£{{number_format($ffe->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}})</small>
                                </td>
                                <td class="d-none d-xl-table-cell">{{$ffe->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{$ffe->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($ffe->purchased_date)->addMonths($ffe->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $ffe->warranty }} Months<br>
                                    @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                        <span class="text-coral">{{ 'Expired' }}</span>
                                    @else
                                        <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                            Remaining</small>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $ffe)
                                            <x-buttons.dropdown-item :route="route('ffes.show', $ffe->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $ffe)
                                            <x-buttons.dropdown-item :route=" route('ffes.edit', $ffe->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $ffe)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$ffe->id"
                                                           :action="route('ffes.destroy', $ffe->id)">
                                                <x-buttons.dropdown-item :data="$ffe->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($ffes->count() === 0)
                            <tr>
                                <td colspan="9" class="text-center">No FFE Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$ffes"/>
                </div>
            </div>
        </div>

        {{-- <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for the
                   Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div> --}}

    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/ffes"/>

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
