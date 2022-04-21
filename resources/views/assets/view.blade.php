@extends('layouts.app')

@section('title', 'View all Computer Equipment')

@section('content')
    <x-wrappers.nav title="Computer Equipment">
        <x-buttons.return :route="route('dashboard')">Dashboard</x-buttons.return>
        @can('recycleBin', \App\Models\Asset::class)
            <x-buttons.recycle :route="route('assets.bin')" :count="\App\Models\Asset::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Asset::class)
            <x-buttons.add :route="route('assets.create')">Equipment</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Asset::class)
            @if ($assets->count() == 1)
                <x-buttons.reports :route="route('asset.showPdf', $assets[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('assets.pdf')">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf" class="btn-blue">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($assets->count() >1)
                <x-form.layout class="d-inline-block" action="/exportassets">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-table" class="btn-yellow"><span class="d-none d-md-inline-block">Export</span>
                    </x-buttons.submit>
                </x-form.layout>
            @endif

            <button class=" btn btn-sm btn-lilac d-inline" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                Bulk Options <i class="fas fa-fw fa-caret-down sidebar-icon"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li>
                    <p class='text-blue text-center p-2 border-bottom border-secondary'>Bulk Options</p>
                </li>
                <li class='my-1'>
                    @can('create', \App\Models\Asset::class)
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

        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        $limit = auth()->user()->location_assets()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_assets()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();

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
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
                        different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->
        {{--
            vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
            model = the Model Name
            relations = Relationship Name e.g $model->relationship or $asset->categories
            table = RAW table name to check if the Schema has the column heading
        --}}
        <x-filters.navigation model="Asset" relations="assets" table="assets"/>
        <x-filters.filter model="Asset" relations="assets" table="assets" :locations="$locations" :statuses="$statuses"
                          :categories="$categories"/>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
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
                            <th class="col-auto text-center d-none d-md-table-cell"><small>Audit Due</small></th>
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
                            <th class="text-center d-none d-md-table-cell"><small>Audit Due</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @if($assets->count() != 0)
                            @foreach($assets as $asset)
                                <tr>
                                    <td>{{$asset->name}}<br>
                                        @if($asset->serial_no != 0)
                                            <small class="d-none d-md-inline-block">
                                                {{ $asset->serial_no ?? 'N/A'}}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                        @if(isset($asset->location->photo->path))
                                            <img src="{{ asset($asset->location->photo->path)}}" height="30px"
                                                 alt="{{$asset->location->name}}"
                                                 title="{{ $asset->location->name }}<br>{{ $asset->room ?? 'Unknown'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'" data-bs-toggle="tooltip" data-bs-placement="top" title="">'
                                                .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $asset->asset_tag ?? 'N/A'}}</td>
                                    <td class="text-center">{{ $asset->model->manufacturer->name ?? 'N/A' }}
                                        <br><small>{{ $asset->model->name ?? 'No Model'}}</small></td>
                                    <td class="d-none d-md-table-cell"
                                        data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                                    <td class="text-center  d-none d-xl-table-cell">
                                        £{{ $asset->purchased_cost }}
                                        <br>
                                        <small>(*£{{ number_format($asset->depreciation_value(), 2)}})</small>
                                    </td>
                                    <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                                    @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                                    <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                        @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                            0 Months<br>
                                            <span class="text-coral">{{ 'Expired' }}</span>
                                        @else
                                            {{ $asset->warranty }} Months

                                            <br>
                                            <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                                Remaining</small>
                                        @endif
                                    </td>
                                    <td class="text-center d-none d-xl-table-cell"
                                        data-sort="{{ strtotime($asset->audit_date)}}">
                                        @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                                            <span
                                                class="text-danger">{{\Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                            <br><small>Audit Overdue</small>
                                        @else
                                            <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                                            @switch(true)
                                                @case($age < 31) <span
                                                    class="text-warning">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                                <br><small>Audit Due Soon</small>
                                                @break
                                                @default
                                                <span
                                                    class="text-secondary">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                                <br><small>Audit due in {{floor($age)}} days</small>
                                            @endswitch
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <x-wrappers.table-settings>
                                            @can('view', $asset)
                                                <x-buttons.dropdown-item :route="route('assets.show', $asset->id)">
                                                    View
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('update', $asset)
                                                <x-buttons.dropdown-item :route=" route('assets.edit', $asset->id)">
                                                    Edit
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('transfer', $asset)
                                                <x-buttons.dropdown-item class="transferBtn"
                                                                         formRequirements="data-model-id='{{$asset->id}}' data-model-tag='{{$asset->asset_tag}}' data-location-from='{{$asset->location->name ?? 'Unallocated' }}' data-location-id='{{ $asset->location_id }}'">
                                                    Transfer
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('dispose', $asset)
                                                <x-buttons.dropdown-item class="disposeBtn"
                                                                         formRequirements="data-model-id='{{$asset->id}}' data-model-name='{{$asset->name ?? 'No name' }}'">
                                                    Dispose
                                                </x-buttons.dropdown-item>
                                            @endcan
                                            @can('delete', $asset)
                                                <x-form.layout method="DELETE" :id="'form'.$asset->id"
                                                               :action="route('assets.destroy', $asset->id)">
                                                    <x-buttons.dropdown-item class="deleteBtn" :data="$asset->id">
                                                        Delete
                                                    </x-buttons.dropdown-item>
                                                </x-form.layout>
                                            @endcan
                                        </x-wrappers.table-settings>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="10" class="text-center">No Assets Returned</td>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$assets"/>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for the
                   Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection
@section('modals')
    <x-modals.dispose model="asset"/>
    <x-modals.transfer :models="$locations" model="asset"/>
    <x-modals.delete/>
    <x-modals.bulk-file title="disposal" :route="route('assets.bulk.disposal')"/>
    <x-modals.bulk-file title="transfer" :route="route('assets.bulk.transfer')"/>
    <x-modals.import :route="route('assets.import')"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
    <script src="{{asset('js/transfer.js')}}"></script>
    <script src="{{asset('js/dispose.js')}}"></script>
    <script src="{{asset('js/filter.js')}}"></script>
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
