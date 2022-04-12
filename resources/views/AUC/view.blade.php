@extends('layouts.app')

@section('title', 'View Assets Under Construction')



@section('content')
    <x-wrappers.nav title="Assets Under Construction">
        <x-buttons.return :route="route('dashboard')">Dashboard</x-buttons.return>

        @can('recycleBin', \App\Models\AUC::class)
            <x-buttons.recycle :route="route('auc.bin')" :count="\App\Models\AUC::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\AUC::class)
            <x-buttons.add :route="route('aucs.create')">Asset Under Construction</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\AUC::class)
            @if ($aucs->count() == 1)
                <x-buttons.reports :route="route('aucs.showPdf', $aucs[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('aucs.pdf')">
                    <x-form.input type="hidden" name="aucs" :label="false" formAttributes="required"
                                  :value="json_encode($aucs->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf" class="btn-blue">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($aucs->count() > 1)
                <x-form.layout class="d-inline-block" action="/export/aucs">
                    <x-form.input type="hidden" name="aucs" :label="false" formAttributes="required"
                                  :value="json_encode($aucs->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span>
                    </x-buttons.submit>
                </x-form.layout>
            @endif
        @endcan
        <div class="dropdown d-inline-block">
            <a class="btn btn-sm btn-lilac dropdown-toggle p-2 p-md-1" href="#" role="button" id="dropdownMenuLink"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Bulk Options
            </a>
            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="dropdownMenuLink">
                @can('create', \App\Models\AUC::class)
                    <x-buttons.dropdown-item id="import">
                        Import
                    </x-buttons.dropdown-item>
                @endcan
            </div>
        </div>
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>

        <p class="mt-5 mb-4">Below are the Assets that are currently under construction within the Central Learning
                             Partnership Trust. You require access to see
                             the assets assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>

        @php

            $limit = auth()->user()->location_auc()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_auc()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

        if(session()->has('auc_min') && session()->has('auc_max')){
            $start_value = session('auc_min');
            $end_value = session('auc_max');
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
        @endphp


        {{-- If there are no Collections return there is not need to display the filter, unless its the filter thats return 0 results --}}
        @if($aucs->count() !== 0 || session('auc_filter') === true)
            <x-filters.navigation model="AUC" relations="auc" table="a_u_c_s"/>
            <x-filters.filter model="AUC" relations="auc" table="a_u_c_s" :locations="$locations"/>
        @endif

    <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th><small>Type</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Value</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Date</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Current Value</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Dep Charge</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Type</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Value</small></th>
                            <th class="text-center"><small>Date</small></th>
                            <th class="text-center"><small>Current Value</small></th>
                            <th class="text-center"><small>Depreciation (Years)</small></th>
                            <th class="text-center"><small>Dep Charge</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($aucs as $auc)
                            <tr>
                                <td class="text-left">{{$auc->name}}</td>
                                <td class="text-left">
                                    @switch($auc->type)
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
                                    @if($auc->location()->exists())
                                        @if($auc->location->photo()->exists())
                                            <img src="{{ asset($auc->location->photo->path)}}" height="30px"
                                                 alt="{{$auc->location->name}}"
                                                 title="{{ $auc->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($auc->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">£{{number_format($auc->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{\Carbon\Carbon::parse($auc->purchased_date)->format('jS M Y')}}</td>
                                <td class="text-center">
                                    £{{number_format($auc->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}</td>
                                <td class="text-center">{{$auc->depreciation}} Years</td>
                                <td class="text-center">
                                    <?php
                                    //If Date is > 1 September the Year is this Year else Year = Last Year

                                    $now = \Carbon\Carbon::now();
                                    $startDate = \Carbon\Carbon::parse('09/01/' . $now->format('Y'));
                                    if(! $startDate->isPast())
                                    {
                                        $startDate->subYear();
                                    }

                                    $bf = $auc->depreciation_value_by_date($startDate);
                                    ?>
                                    £{{number_format( (float) $auc->purchased_cost - $bf, 2, '.', ',' )}}
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $auc)
                                            <x-buttons.dropdown-item :route="route('aucs.show', $auc->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $auc)
                                            <x-buttons.dropdown-item :route=" route('aucs.edit', $auc->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan

                                        <a href="{{route('auc.move', $auc->id)}}" class="dropdown-item">Move to
                                                                                                        Property</a>

                                        @can('delete', $auc)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$auc->id"
                                                           :action="route('aucs.destroy', $auc->id)">
                                                <x-buttons.dropdown-item :data="$auc->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($aucs->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Assets Under Construction Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$aucs"/>
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
    <x-modals.import route="/import/aucs"/>

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
