@extends('layouts.app')

@section('title', 'Accessories')


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
                    <x-buttons.submit class="btn-blue">Generate Report</x-buttons.submit>
                </x-form.layout>

            @endif
            @if($accessories->count() >1)
                <x-buttons.export route="/exportaccessories"/>
            @endif
        @endcan
        @can('import', \App\Models\Accessory::class)
            <x-buttons.import id="import"/>
        @endcan
        <button class=" btn btn-sm btn-lilac d-inline" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                aria-expanded="false">
            Bulk Options <i class="fas fa-fw fa-caret-down sidebar-icon"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li>
                <p class='text-blue text-center p-2 border-bottom border-secondary'>Bulk Options</p>
            </li>
            <li class='my-1'>
                @can('create', \App\Models\Accessory::class)
                    <x-buttons.dropdown-item id="import">
                        Import
                    </x-buttons.dropdown-item>
                @endcan
                <x-buttons.dropdown-item
                    form-requirements=" data-bs-toggle='modal' data-bs-target='#bulkDisposalModal'">
                    Dispose
                </x-buttons.dropdown-item>
                <x-buttons.dropdown-item
                    form-requirements=" data-bs-toggle='modal' data-bs-target='#bulkTransferModal'">
                    Transfer
                </x-buttons.dropdown-item>
            </li>
        </ul>

    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        $limit = auth()->user()->location_accessories()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_accessories()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

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
                        different options and locations can be created, updated, and deleted.</p>

        <!-- DataTales Example -->
        <x-filters.navigation model="Accessory" relations="accessories" table="accessories" :filter=$filter/>
            <x-filters.filter model="Accessory" relations="accessories" table="accessories" :filter=$filter
                              :locations=$locations :statuses=$statuses :categories="$categories"/>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive" id="table">
                        <table id="usersTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-4 col-md-2"><small>Item</small></th>
                                <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                                <th class="text-center col-1 col-md-auto"><small>Tag</small></th>
                                <th class="text-center col-5 col-md-auto"><small>Manufacturer</small></th>
                                <th class="d-none d-xl-table-cell"><small>Date</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Cost</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Supplier</small></th>
                                <th class="text-center col-auto d-none d-xl-table-cell"><small>Warranty (M)</small></th>
                                <th class="col-auto text-center d-none d-md-table-cell"><small>Status</small></th>
                                <th class="text-right col-1"><small>Options</small></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th><small>Item</small></th>
                                <th><small>Location</small></th>
                                <th class="text-center"><small>Tag</small></th>
                                <th class="text-center"><small>Manufacturer</small></th>
                                <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Cost</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Supplier</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Warranty (M)</small></th>
                                <th class="text-center d-none d-md-table-cell"><small>Status</small></th>
                                <th class="text-right"><small>Options</small></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($accessories as $accessory)
                                <tr>
                                    <td>
                                        {{$accessory->name}}
                                        <br>
                                        @if($accessory->serial_no != 0)
                                            <small>{{$accessory->serial_no ?? 'N/A'}}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($accessory->location->photo->path))
                                            <img src="{{ asset($accessory->location->photo->path)}}" height="30px"
                                                 alt="{{$accessory->location->name}}"
                                                 title="{{ $accessory->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($accessory->location->icon ?? '#666').'">'
                                                .strtoupper(substr($accessory->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                        @if($accessory->room != "")<br><small>Room: {{ $accessory->room }}</small>@endif
                                    </td>
                                    <td>{{ $accessory->asset_tag}}</td>
                                    <td class="text-center">{{ $accessory->model ?? 'No Model'}}
                                        <br><small>{{$accessory->manufacturer->name ?? "N/A"}}</small></td>
                                    <td class="d-none d-xl-table-cell">{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                                    <td class="text-center d-none d-xl-table-cell">
                                        £{{$accessory->purchased_cost}} @if($accessory->donated == 1) <span
                                            class="text-sm">*Donated</span> @endif
                                        <br>
                                      
                                    </td>
                                    <td class="d-none d-xl-table-cell">{{$accessory->supplier->name ?? 'N/A'}}</td>
                                    @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                                    <td class="text-center d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                        {{ $accessory->warranty }} Months<br>
                                        @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                            <span class="text-coral">{{ 'Expired' }}</span>
                                        @else
                                            <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                                Remaining</small>
                                        @endif
                                    </td>
                                    <td class="text-center d-none d-xl-table-cell"
                                        style="color: {{$accessory->status->colour ?? '#666'}};">
                                        <i class="{{$accessory->status->icon ?? 'fas fa-circle'}}"></i> {{ $accessory->status->name ?? 'No Status' }}
                                    </td>
                                    <td class="text-right">
                                        <x-wrappers.table-settings>
                                            @can('view', $accessory)
                                                <x-buttons.dropdown-item
                                                    :route="route('accessories.show', $accessory->id)">
                                                    View
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('update', $accessory)
                                                <x-buttons.dropdown-item
                                                    :route=" route('accessories.edit', $accessory->id)">
                                                    Edit
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('transfer', $accessory)
                                                <x-buttons.dropdown-item class="transferBtn"
                                                                         formRequirements="data-model-id='{{$accessory->id}}' data-location-from='{{$accessory->location->name ?? 'Unallocated' }}' data-location-id='{{ $accessory->location_id }}'">
                                                    Transfer
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('dispose', $accessory)
                                                <x-buttons.dropdown-item class="disposeBtn"
                                                                         formRequirements="data-model-id='{{$accessory->id}}' data-model-name='{{$accessory->name ?? 'No name' }}'">
                                                    Dispose
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('delete', $accessory)
                                                <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                               :id="'form'.$accessory->id"
                                                               :action="route('accessories.destroy', $accessory->id)">
                                                    <x-buttons.dropdown-item :data="$accessory->id" class="deleteBtn">
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
                        <x-paginate :model="$accessories"/>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h4>Help with Accessories</h4>
                    <p>Click <a href="{{route("documentation.index").'#collapseEightAccessory'}}">here</a> for the
                       Documentation on Accessories on Importing ,Exporting , Adding , Removing!</p>
                </div>
            </div>

    </section>

@endsection

@section('modals')
    <x-modals.bulk-file title="disposal" route=""/>
    <x-modals.bulk-file title="transfer" route=""/>
    <x-modals.delete/>
    <x-modals.transfer :models="$locations" model="accessory"/>
    <x-modals.dispose model="accessory"/>
    <x-modals.import route="/importacessories"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}" defer></script>
    <script src="{{asset('js/import.js')}}" defer></script>
    <script src="{{asset('js/transfer.js')}}" defer></script>
    <script src="{{asset('js/dispose.js')}}" defer></script>
    <script src="{{asset('js/filter.js')}}" defer></script>
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
