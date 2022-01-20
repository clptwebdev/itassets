@extends('layouts.app')

@section('title', 'View Miscellaneous')

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
    <x-wrappers.nav title="Miscellaneous">
        @can('viewAny', \App\Models\Miscellanea::class)
            <x-buttons.recycle :route="route('miscellaneous.bin')"
                               :count="\App\Models\Miscellanea::onlyTrashed()->count()"/>
        @endcan
        @can('create', \App\Models\Miscellanea::class)
            <x-buttons.add :route="route('miscellaneous.create')">Miscellanea</x-buttons.add>

        @endcan
        @can('viewAny', \App\Models\Miscellanea::class)
            @if ($miscellaneous->count() == 1)
                <x-buttons.reports :route="route('miscellaneous.showPdf', $miscellaneous[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('miscellaneous.pdf')">
                    <x-form.input type="hidden" name="miscellaneous" :label="false" formAttributes="required"
                                  :value="json_encode($miscellaneous->pluck('id'))"/>
                    <x-buttons.submit>Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($miscellaneous->count() >1)
                <x-buttons.export route="/exportmiscellaneous"/>
            @endif
        @endcan
        @can('create', \App\Models\Miscellanea::class)
            <x-buttons.import id="import"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        if(auth()->user()->role_id == 1){
            $limit = \App\Models\Miscellanea::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) DESC')->pluck('purchased_cost')->first();
            $floor = \App\Models\Miscellanea::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) ASC')->pluck('purchased_cost')->first();
        }else{
            $limit = auth()->user()->location_miscellaneous()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_miscellaneous()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
        }
        if(session()->has('amount')){
            $amount = str_replace('£', '', session('amount'));
            $amount = explode(' - ', $amount);
            $start_value = intval($amount[0]);
            $end_value = intval($amount[1]);
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
    @endphp
    <section>
        <p class="mb-4">Below are the different miscellaneous stored in the management system. Each has
            different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        @if($miscellaneous->count() != 0)
        <x-filters.navigation model="Miscellanea" :filter=$filter/>
        <x-filters.filter model="Miscellanea" relations="components" :filter=$filter :locations=$locations
                          :statuses=$statuses :categories=$categories/>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-2 col-md-1 text-center"><small>Location</small></th>
                            <th class="text-center col-5 col-md-2 d-none d-sm-table-cell"><small>Manufacturers</small></th>
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
                        @foreach($miscellaneous as $miscellanea)
                            <tr>
                                <td>{{$miscellanea->name}}
                                    @if($miscellanea->serial_no != 0)
                                    <br>
                                    <small>{{$miscellanea->serial_no}}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($miscellanea->location()->exists())
                                        @if($miscellanea->location->photo()->exists())
                                            <img src="{{ asset($miscellanea->location->photo->path)}}" height="30px"
                                                 alt="{{$miscellanea->location->name}}"
                                                 title="{{ $miscellanea->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($miscellanea->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center d-none d-sm-inline-block">{{$miscellanea->manufacturer->name ?? "N/A"}}</td>
                                <td class="d-none d-xl-table-cell" data-sort="{{ strtotime($miscellanea->purchased_date)}}">{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center d-none d-xl-table-cell">
                                    £{{$miscellanea->purchased_cost}} @if($miscellanea->donated == 1) <span
                                        class="text-sm">*Donated</span> @endif
                                    @if($miscellanea->depreciation()->exists())
                                        <br>
                                        @php
                                            $eol = Carbon\Carbon::parse($miscellanea->purchased_date)->addYears($miscellanea->depreciation->years);
                                            if($eol->isPast()){
                                                $dep = 0;
                                            }else{

                                                $age = Carbon\Carbon::now()->floatDiffInYears($miscellanea->purchased_date);
                                                $percent = 100 / $miscellanea->depreciation->years;
                                                $percentage = floor($age)*$percent;
                                                $dep = $miscellanea->purchased_cost * ((100 - $percentage) / 100);
                                            }
                                        @endphp
                                        <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
                                </td>
                                <td class="d-none d-xl-table-cell">{{$miscellanea->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{$miscellanea->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $miscellanea->warranty }} Months<br>
                                    @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                        <span class="text-coral">{{ 'Expired' }}</span>
                                    @else
                                        <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                            Remaining</small>
                                    @endif
                                </td>
                                <td class="text-right">
                               <x-wrappers.table-settings>
                                            @can('view', $miscellanea)
                                                <x-buttons.dropdown-item :route="route('miscellaneous.show', $miscellanea->id)">
                                                    View
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('update', $miscellanea)
                                                    <x-buttons.dropdown-item :route=" route('miscellaneous.edit', $miscellanea->id)">
                                                        Edit
                                                    </x-buttons.dropdown-item>
                                            @endcan
                                            @can('delete', $miscellanea)
                                                <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$miscellanea->id" :action="route('miscellaneous.destroy', $miscellanea->id)">
                                                    <x-buttons.dropdown-item :data="$miscellanea->id" class="deleteBtn" >
                                                        Delete
                                                    </x-buttons.dropdown-item>
                                                </x-form.layout>
                                            @endcan
                               </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-paginate :model="$miscellaneous"/>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with miscellaneous</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseTenMiscellaneous'}}">here</a> for the
                    Documentation on miscellaneous on importing ,exporting ,Adding and Removing!</p>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    <x-modals.delete>Miscellanea</x-modals.delete>
    <x-modals.import route="/importmiscellaneous"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
    <script src="{{asset('js/filter.js')}}"></script>
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
