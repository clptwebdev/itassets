@extends('layouts.app')

@section('title', 'Accessories')

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
    <x-wrappers.nav title="Accessories">
        @can('recycleBin', \App\Models\Accessory::class)
            <x-buttons.recycle :route="route('accessories.bin')"
                               :count="\App\Models\Accessory::onlyTrashed()->count()"/>
        @endcan
        @can('create', \App\Models\Accessory::class)
            <x-buttons.add :route="route('accessories.create')">Accessory</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Accessory::class)
            @if ($accessories->count() == 1)
                <x-buttons.reports :route="route('accessories.showPdf', $accessories[0]->id)"/>

            @else
                <x-form.layout class="d-inline-block" :action="route('accessories.pdf')" method="POST">
                    <x-form.input type="hidden" name="accessories" :label="false"
                                  :value="json_encode($accessories->pluck('id'))"/>
                    <x-buttons.submit>Generate Report</x-buttons.submit>
                </x-form.layout>

            @endif
            @if($accessories->count() >1)
                <x-buttons.export route="/exportaccessories"/>
            @endif
        @endcan
        @can('import', \App\Models\Accessory::class)
            <x-buttons.import id="import"/>
        @endcan

    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        if(auth()->user()->role_id == 1){
            $limit = \App\Models\Accessory::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) DESC')->pluck('purchased_cost')->first();
            $floor = \App\Models\Accessory::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) ASC')->pluck('purchased_cost')->first();
        }else{
            $limit = auth()->user()->location_accessories()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_accessories()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
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


        <p class="mb-4">Below are the different Accessories stored in the management system. Each has
            different options and locations can created, updated, and deleted.</p>

        <!-- DataTales Example -->
        <x-filters.navigation model="Accessory" :filter=$filter />
            <x-filters.filter model="Accessory" relations="accessories" :filter=$filter :locations=$locations
                              :statuses=$statuses :categories="$categories"/>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th><small>Name</small></th>
                                <th class="text-center"><small>Location</small></th>
                                <th class="text-center"><small>Model</small></th>
                                <th><small>Date</small></th>
                                <th class="text-center"><small>Cost (Value)</small></th>
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
                                <th class="text-center"><small>Model</small></th>
                                <th><small>Purchased Date</small></th>
                                <th class="text-center"><small>Cost (Value)</small></th>
                                <th><small>Supplier</small></th>
                                <th class="text-center"><small>Status</small></th>
                                <th class="text-center"><small>Warranty</small></th>
                                <th class="text-right"><small>Options</small></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($accessories as $accessory)
                                <tr>
                                    <td>{{$accessory->name}}
                                        <br>
                                        <small>{{$accessory->serial_no}}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($accessory->location->photo()->exists())
                                            <img src="{{ asset($accessory->location->photo->path)}}" height="30px"
                                                 alt="{{$accessory->location->name}}"
                                                 title="{{ $accessory->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($accessory->location->icon ?? '#666').'">'
                                                .strtoupper(substr($accessory->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                        @if($accessory->room != "")<br><small>Room: {{ $accessory->room }}</small>@endif
                                    </td>
                                    <td class="text-center">{{ $accessory->model ?? 'No Model'}}
                                        <br><small>{{$accessory->manufacturer->name ?? "N/A"}}</small></td>
                                    <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                                    <td class="text-center">
                                        £{{$accessory->purchased_cost}} @if($accessory->donated == 1) <span
                                            class="text-sm">*Donated</span> @endif
                                        <br>
                                        <small>(*£{{ number_format($accessory->depreciation_value(), 2)}})</small>
                                    </td>
                                    <td>{{$accessory->supplier->name ?? 'N/A'}}</td>
                                    <td class="text-center" style="color: {{$accessory->status->colour ?? '#666'}};">
                                        <i class="{{$accessory->status->icon ?? 'fas fa-circle'}}"></i> {{ $accessory->status->name ?? 'No Status' }}
                                    </td>
                                    @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                                    <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                        {{ $accessory->warranty }} Months<br>
                                        @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                            <span class="text-coral">{{ 'Expired' }}</span>
                                        @else
                                            <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                                Remaining</small>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown no-arrow">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                               id="dropdownMenu{{$accessory->id}}Link"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div
                                                class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                                aria-labelledby="dropdownMenu{{$accessory->id}}Link">
                                                <div class="dropdown-header">Accessory Options:</div>
                                                @can('view', $accessory)
                                                    <a href="{{ route('accessories.show', $accessory->id) }}"
                                                       class="dropdown-item">View</a>
                                                @endcan
                                                @can('update', $accessory)
                                                    <a href="{{ route('accessories.edit', $accessory->id) }}"
                                                       class="dropdown-item">Edit</a>
                                                @endcan
                                                @can('transfer', $accessory)
                                                    <a href="#"
                                                       class="dropdown-item transferBtn"
                                                       data-model-id="{{$accessory->id}}"
                                                       data-location-from="{{$accessory->location->name ?? 'Unallocated' }}"
                                                       data-location-id="{{ $accessory->location_id }}"
                                                    >Transfer</a>
                                                @endcan
                                                @can('dispose', $accessory)
                                                    <a href="#"
                                                       class="dropdown-item disposeBtn"
                                                       data-model-id="{{$accessory->id}}"
                                                       data-model-name="{{$accessory->name ?? 'No name'}}"
                                                    >Dispose</a>
                                                @endcan
                                                @can('delete', $accessory)
                                                    <form id="form{{$accessory->id}}"
                                                          action="{{ route('accessories.destroy', $accessory->id) }}"
                                                          method="POST" class="d-block p-0 m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="deleteBtn dropdown-item" href="#"
                                                           data-id="{{$accessory->id}}">Delete</a>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <x-paginate :model="$accessories"/>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h4>Help with Accessories</h4>
                    <p>Click <a href="{{route("documentation.index").'#collapseEightAccessory'}}">here</a> for a the
                        Documentation on Accessories on Importing ,Exporting , Adding , Removing!</p>
                </div>
            </div>

    </section>

@endsection

@section('modals')
        <x-modals.delete />
        <x-modals.transfer :models="$locations"/>
        <x-modals.dispose />
        <x-modals.import route="/importacessories"/>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script  src="{{asset('js/delete.js')}}"></script>
    <script  src="{{asset('js/import.js')}}"></script>
    <script  src="{{asset('js/transfer.js')}}"></script>
    <script  src="{{asset('js/dispose.js')}}"></script>
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
