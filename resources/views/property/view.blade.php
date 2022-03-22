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
        @can('recycleBin', \App\Models\Property::class)
            <x-buttons.recycle :route="route('property.bin')" :count="\App\Models\Property::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Property::class)
            <x-buttons.add :route="route('properties.create')">Property</x-buttons.add>
        @endcan
     @can('generatePDF', \App\Models\Asset::class)
         @if ($properties->count() == 1)
             <x-buttons.reports :route="route('properties.showPdf', $properties[0]->id)"/>
         @else
             <x-form.layout class="d-inline-block" :action="route('properties.pdf')">
                 <x-form.input type="hidden" name="property" :label="false" formAttributes="required"
                               :value="json_encode($properties->pluck('id'))"/>
                 <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
             </x-form.layout>
         @endif
         @if($properties->count() >1)
             <x-form.layout class="d-inline-block" action="/export/properties">
                 <x-form.input type="hidden" name="properties" :label="false" formAttributes="required"
                               :value="json_encode($properties->pluck('id'))"/>
                 <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span></x-buttons.submit>
             </x-form.layout>
         @endif
         <div class="dropdown show d-inline">
             <a class="btn btn-sm btn-lilac dropdown-toggle p-2 p-md-1" href="#" role="button" id="dropdownMenuLink"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 Bulk Options
             </a>
             <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="dropdownMenuLink">
                 @can('import', \App\Models\Property::class)
                     <x-buttons.dropdown-item id="import">
                         Import
                     </x-buttons.dropdown-item>
                 @endcan
                 <x-buttons.dropdown-item form-requirements=" data-toggle='modal' data-target='#bulkDisposalModal'">
                     Dispose
                 </x-buttons.dropdown-item>
             </div>
         </div>
     @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are the Properties belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the property assigned to the different locations. If you think you have the incorrect
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

        <x-filters.navigation model="Property" relations="property" table="properties"/>
        <x-filters.filter model="Property" relations="property" table="properties" :locations="$locations"/>

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
                                <td class="text-center">£{{number_format($property->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{\Carbon\Carbon::parse($property->purchased_date)->format('jS M Y')}}</td>
                                <td class="text-center">
                                    £{{number_format($property->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}</td>
                                <td class="text-center">{{$property->depreciation}} Years</td>
                                <td class="text-center">
                                    <?php
                                        //If Date is > 1 September the Year is this Year else Year = Last Year
                        
                                        $now = \Carbon\Carbon::now();
                                        $startDate = \Carbon\Carbon::parse('09/01/'.$now->format('Y'));
                                        $endDate = \Carbon\Carbon::parse('08/31/'.\Carbon\Carbon::now()->addYear()->format('Y'));
                                        if(!$startDate->isPast()){
                                            $startDate->subYear();
                                            $endDate->subYear();
                                        }

                                        $bf = $property->depreciation_value($startDate);
                                        $cf = $property->depreciation_value($endDate);
                                    ?>
                                    £{{number_format( (float) $property->purchased_cost - $bf, 2, '.', ',' )}}    
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $property)
                                            <x-buttons.dropdown-item :route="route('properties.show', $property->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $property)
                                            <x-buttons.dropdown-item :route=" route('properties.edit', $property->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan

                                        @can('archive', $property)
                                                <x-buttons.dropdown-item class="disposeBtn"
                                                                         formRequirements="data-model-id='{{$property->id}}' data-model-name='{{$property->name ?? 'No name' }}'">
                                                    Archive
                                                </x-buttons.dropdown-item>
                                            @endcan

                                        @can('delete', $property)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                           :id="'form'.$property->id"
                                                           :action="route('properties.destroy', $property->id)">
                                                <x-buttons.dropdown-item :data="$property->id" class="deleteBtn">
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
                                <td colspan="9" class="text-center">No Property Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$properties"/>
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
    <x-modals.dispose model="property"/>
    <x-modals.import route="/import/properties"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('js/filter.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/dispose.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
    <script>
        $(function () {
            $("#slider-range").slider({
                range: true,
                min: {{ floor($floor)}},
                max: {{ round($limit)}},
                values: [{{ floor($start_value)}}, {{ round($end_value)}}],
                slide: function (event, ui) {
                    $("#amount").val("£" + ui.values[0] + " - £" + ui.values[1]);
                }
            });
            $("#amount").val("£" + $("#slider-range").slider("values", 0) +
                " - £" + $("#slider-range").slider("values", 1));
        });
    </script>
@endsection
