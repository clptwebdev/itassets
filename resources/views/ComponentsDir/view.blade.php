@extends('layouts.app')

@section('title', 'View Components')

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
<x-wrappers.nav title="Components">
            @can('recycleBin', \App\Models\Component::class)
                <x-buttons.recycle :route="route('components.bin')" :count="\App\Models\Component::onlyTrashed()->count()"/>
            @endcan
            @can('create' , \App\Models\Component::class)
                <x-buttons.add :route="route('components.create')">Component(s)</x-buttons.add>
            @endcan
            @can('export', \App\Models\Component::class)
                @if ($components->count() == 1)
                    <x-buttons.reports :route="route('components.showPdf', $components[0]->id)"/>
                @else
                    <x-form.layout class="d-inline-block" :action="route('components.pdf')">
                        <x-form.input   type="hidden" name="components" :label="false" formAttributes="required"
                                      :value="json_encode($components->pluck('id'))"/>
                        <x-buttons.submit>Generate Report</x-buttons.submit>
                    </x-form.layout>
                @endif
                @if($components->count() >1)
                    <x-buttons.export route="/exportcomponents"/>
                @endif
            @endcan
            @can('import' , \App\Models\Component::class)
                <x-buttons.import id="import" />
            @endcan
</x-wrappers.nav>
<x-handlers.alerts/>
    @php
        if(auth()->user()->role_id == 1){
            $limit = \App\Models\Component::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) DESC')->pluck('purchased_cost')->first();
            $floor = \App\Models\Component::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) ASC')->pluck('purchased_cost')->first();
        }else{
            $limit = auth()->user()->location_components()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_components()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
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
        <p class="mb-4">Below are the different Components stored in the management system. Each has
            different options and locations can created, updated, and deleted.</p>

        <x-filters.navigation model="Component" :filter="$filter"/>
        <x-filters.filter model="Component" relations="components" :filter="$filter" :locations="$locations"
                          :statuses="$statuses" :categories="$categories"/>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($components as $component)

                            <tr>
                                <td>{{$component->name}}
                                    <br>
                                    <small>{{$component->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if(isset($component->location->photo->path))
                                        <img src="{{ asset($component->location->photo->path)}}" height="30px"
                                             alt="{{$component->location->name}}"
                                             title="{{ $component->location->name ?? 'Unnassigned'}}"/>'
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($component->location->icon ?? '#666').'">'
                                            .strtoupper(substr($component->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{$component->manufacturer->name ?? "N/A"}}</td>
                                <td>{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                                <td>{{$component->purchased_cost}}</td>
                                <td>{{$component->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center">{{$component->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $component->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Component Options:</div>
                                            <a href="{{ route('components.show', $component->id) }}"
                                               class="dropdown-item">View</a>
                                            @can('update', $component)
                                                <a href="{{ route('components.edit', $component->id) }}"
                                                   class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $component)
                                                <form id="form{{$component->id}}"
                                                      action="{{ route('components.destroy', $component->id) }}"
                                                      method="POST" class="d-block p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$component->id}}">Delete</a>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-paginate :model="$components"/>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Components </h4>
                <p>Click <a href="{{route("documentation.index").'#collapseNineComponent'}}">here</a> for a the
                    Documentation on Components on Importing ,Exporting , Adding , Removing!</p>

            </div>
        </div>
    </section>

@endsection

@section('modals')
    <x-modals.delete> Component</x-modals.delete>
    <x-modals.import route="/importcomponents"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script  src="{{asset('js/delete.js')}}"></script>
    <script  src="{{asset('js/import.js')}}"></script>
    <script  src="{{asset('js/filter.js')}}"></script>
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
