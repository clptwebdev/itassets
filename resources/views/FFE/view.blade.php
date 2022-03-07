@extends('layouts.app')

@section('title', 'View Furniture, Fixtures and Equipment (FFE)')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
          integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css"
          integrity="sha512-9h7XRlUeUwcHUf9bNiWSTO9ovOWFELxTlViP801e5BbwNJ5ir9ua6L20tEroWZdm+HFBAWBLx2qH4l4QHHlRyg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endsection

@section('content')
    <x-wrappers.nav title="Furniture, Fixtures and Equipment (FFE)">
        @can('recycleBin', \App\Models\FFE::class)
            <x-buttons.recycle :route="route('ffe.bin')" :count="\App\Models\FFE::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\FFE::class)
            <x-buttons.add :route="route('ffes.create')">FFE</x-buttons.add>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below is the Furniture, Fixtures and Equipment(FFE) that is currently located within the different schools in the Central Learning Partnership Trust. You require access to see
            the FFE assigned to the different locations. If you think you have the incorrect permissions, please contact apollo@clpt.co.uk
        </p>

        @php
        if(auth()->user()->role_id == 1){
            $limit = \App\Models\FFE::orderByRaw('CAST(purchased_cost as DECIMAL(11,2)) DESC')->pluck('purchased_cost')->first();
            $floor = \App\Models\FFE::orderByRaw('CAST(purchased_cost as DECIMAL(11,2)) ASC')->pluck('purchased_cost')->first();
        }else{
            $limit = auth()->user()->location_property()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_property()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
        }
        if(session()->has('auc_amount')){
            $amount = str_replace('£', '', session('ffe_amount'));
            $amount = explode(' - ', $amount);
            $start_value = intval($amount[0]);
            $end_value = intval($amount[1]);
        }else{
            $start_value = $floor;
            $end_value = $limit;
        }
        @endphp

        {{-- If there are no Collections return there is not need to display the filter, unless its the filter thats return 0 results --}}
        @if(!session()->has('ffe_filter') && $ffes->count() !== 0)
            <x-filters.navigation model="FFE" relations="ffe" table="f_f_e_s" />
            <x-filters.filter  model="FFE" relations="ffe" table="f_f_e_s" :locations="$locations"/>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
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
                                <td class="text-center d-none d-sm-inline-block">{{$ffe->manufacturer->name ?? "N/A"}}</td>
                                <td class="d-none d-xl-table-cell" data-sort="{{ strtotime($ffe->purchased_date)}}">{{\Carbon\Carbon::parse($ffe->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center d-none d-xl-table-cell">
                                    £{{$ffe->purchased_cost}} @if($ffe->donated == 1) <span
                                        class="text-sm">*Donated</span> @endif
                                    @if($ffe->depreciation()->exists())
                                        <br>
                                        @php
                                            $eol = Carbon\Carbon::parse($ffe->purchased_date)->addYears($ffe->depreciation->years);
                                            if($eol->isPast()){
                                                $dep = 0;
                                            }else{

                                                $age = Carbon\Carbon::now()->floatDiffInYears($ffe->purchased_date);
                                                $percent = 100 / $ffe->depreciation->years;
                                                $percentage = floor($age)*$percent;
                                                $dep = $ffe->purchased_cost * ((100 - $percentage) / 100);
                                            }
                                        @endphp
                                        <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
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
                                                <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$ffe->id" :action="route('ffes.destroy', $ffe->id)">
                                                    <x-buttons.dropdown-item :data="$ffe->id" class="deleteBtn" >
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

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/filter.js')}}"></script>
<script src="{{asset('js/delete.js')}}"></script>
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
