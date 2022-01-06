@extends('layouts.app')

@section('title', 'View all Assets')

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
    <x-wrappers.nav title="Assets">
        @can('recycleBin', \App\Models\Asset::class)
            <x-buttons.recycle :route="route('assets.bin')" :count="\App\Models\Asset::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Asset::class)
            <x-buttons.add :route="route('assets.create')">Assets(s)</x-buttons.add>
        @endcan
        @can('generatePDF', \App\Models\Asset::class)
            @if ($assets->count() == 1)
                <x-buttons.reports :route="route('asset.showPdf', $assets[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('assets.pdf')">
                    <x-form.input   type="hidden" name="assets" :label="false" formAttributes="required"
                                    :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit>Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($assets->count() >1)
                    <x-form.layout class="d-inline-block" action="/exportassets">
                        <x-form.input   type="hidden" name="assets" :label="false" formAttributes="required"
                                        :value="json_encode($assets->pluck('id'))"/>
                        <x-buttons.submit class="btn-yellow">export</x-buttons.submit>
                    </x-form.layout>
            @endif
<<<<<<< HEAD
            <div class="dropdown show d-inline">
                <a class="btn btn-sm btn-lilac dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 Bulk Options
                </a>

                <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="dropdownMenuLink">
                    @can('create', \App\Models\Asset::class)
                    <a id="import" class="dropdown-item"> Import</a>
                    @endcan
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#bulkDisposalModal">Dispose</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#bulkTransferModal">Transfer</a>
=======
        @endcan
        @can('create' , \App\Models\Asset::class)
                <div class="dropdown show d-inline">
                    <a class="btn btn-sm btn-grey dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bulk Options
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        @can('create', \App\Models\Asset::class)
                            <a id="import" class="dropdown-item"> Import</a>
                        @endcan
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#bulkDisposalModal">Dispose</a>
                        <a class="dropdown-item" href="#">Transfer</a>
                    </div>
>>>>>>> b776c015070e13c44fce8b97db9ea976cfd4cf48
                </div>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        if(auth()->user()->role_id == 1){
            $limit = \App\Models\Asset::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) DESC')->pluck('purchased_cost')->first();
            $floor = \App\Models\Asset::orderByRaw('CAST(purchased_cost as DECIMAL(8,2)) ASC')->pluck('purchased_cost')->first();
        }else{
            $limit = auth()->user()->location_assets()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
            $floor = auth()->user()->location_assets()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
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
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
            different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->
        <x-filters.navigation model="Asset" :filter="$filter" />
        <x-filters.filter model="Asset" relations="assets" :filter="$filter" :locations="$locations" :statuses="$statuses" :categories="$categories"/>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-9 col-md-2"><small>Item</small></th>
                            <th class="col-1 col-md-auto"><small>Location</small></th>
                            <th class="text-centercol-1 col-md-auto"><small>Tag</small></th>
                            <th class="text-centerd-none d-xl-table-cell"><small>Manufacturer</small></th>
                            <th class="d-none d-xl-table-cell"><small>Date</small></th>
                            <th class="text-centerd-none d-xl-table-cell"><small>Cost</small></th>
                            <th class="text-centerd-none d-xl-table-cell"><small>Supplier</small></th>
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
                            <th class="text-center d-none d-xl-table-cell"><small>Manufacturer</small></th>
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
                                        <img src="{{ asset($asset->location->photo->path)}}" height="30px" alt="{{$asset->location->name}}" title="{{ $asset->location->name }}<br>{{ $asset->room ?? 'Unknown'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'" data-toggle="tooltip" data-placement="top" title="">'
                                            .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{ $asset->asset_tag ?? 'N/A'}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{ $asset->model->manufacturer->name ?? 'N/A' }}<br><small>{{ $asset->model->name ?? 'No Model'}}</small></td>
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

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
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
                                            <x-buttons.dropdown-item class="transferBtn" formRequirements="data-model-id='{{$asset->id}}' data-location-from='{{$asset->location->name ?? 'Unallocated' }}' data-location-id='{{ $asset->location_id }}'">
                                                Transfer
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('dispose', $asset)
                                            <x-buttons.dropdown-item class="disposeBtn" formRequirements="data-model-id='{{$asset->id}}' data-model-name='{{$asset->name ?? 'No name' }}'">
                                                Dispose
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $asset)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$asset->id" :action="route('assets.destroy', $asset->id)">
                                                <x-buttons.dropdown-item :data="$asset->id" class="deleteBtn" >
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
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for the Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection
@section('modals')
<<<<<<< HEAD
    <!-- Disposal Modal-->
    <div class="modal fade bd-example-modal-lg" id="requestDisposal" tabindex="-1" role="dialog" aria-labelledby="requestDisposalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('request.disposal')}}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestDisposalLabel">Request to Dispose of the Asset?
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @csrf
                            <input name="model_type" type="hidden" value="asset">
                            <input id="dispose_id" name="model_id" type="hidden" value="">
                            <input type="text" value="" id="asset_name" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label for="disposal_date">Date of Disposal</label>
                            <input type="date" value="" id="disposed_date" name="disposed_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="notes">Reasons for:</label>
                            <textarea name="notes" class="form-control" rows="5"></textarea>
                        </div>
                        <small>This will send a request to the administrator. The administrator will then decide to approve or reject the request. You will be notified via email.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-coral" type="submit">Request Disposal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Transfer Modal-->
    <div class="modal fade bd-example-modal-lg" id="requestTransfer" tabindex="-1" role="dialog" aria-labelledby="requestTransferLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('request.transfer')}}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestTransferLabel">Request to Transfer this Asset to another Location?
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @csrf
                            <input name="model_type" type="hidden" value="asset">
                            <input id="model_id" name="model_id" type="hidden" value="">
                            <input id="location_id" name="location_from" type="hidden" value="">
                            <input id="location_from" type="text" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="disposal_date">Date of Transfer</label>
                            <input type="date" value="" id="transfer_date" name="transfer_date" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="School Location">Transfer to:</label><span
                                class="text-danger">*</span>
                            <select type="text"
                                class="form-control mb-3 @if($errors->has('location_id')){{'border-danger'}}@endif"
                                name="location_to" required>
                                <option value="0" selected>Please select a Location</option>
                                @foreach($locations as $location)
                                <option value="{{$location->id}}" @if(old('location_id') == $location->id){{ 'selected'}}@endif>{{$location->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Additional Comments:</label>
                            <textarea name="notes" class="form-control" rows="5"></textarea>
                        </div>
                        <small>This will send a request to the administrator. The administrator will then decide to approve or reject the request. You will be notified via email.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-lilac" type="submit">@if(auth()->user()->role_id == 1){{ 'Transfer' }} @else {{ 'Request Transfer' }} @endif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeAssetModal" tabindex="-1" role="dialog"
         aria-labelledby="removeassetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeassetModalLabel">Are you sure you want to send this asset to the
                        recycle bin?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="asset-id" type="hidden" value="">
                    <p>Select "Send to Bin" to send this asset to the recycle bin from the system.</p>
                    <small class="text-coral">**This is not permanent and the Asset can be restored. Whilst in the
                        Recycle Bin, the Asset will not be included in any statistics and data.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Send to Bin</button>
                </div>
            </div>
        </div>
    </div>
    {{--//import--}}
    <div class="modal fade bd-example-modal-lg" id="importManufacturerModal" tabindex="-1" role="dialog"
         aria-labelledby="importManufacturerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importManufacturerModalLabel">Importing Data</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="/importassets" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <p>Select "import" to add Assets to the system.</p>
                            <input class="form-control shadow-sm"
                                type="file" placeholder="Upload here" name="csv" accept=".csv" id="importEmpty" required>
                        </div>
                        <div class="modal-footer">
                            @if(session('import-error'))
                                <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                            @endif
                            <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/Eb2RbyCNk_hOuTfMOufGpMsBl0yUs1ZpeCjkCm6YnLfN9Q?e=4t5BVO"
                            target="_blank" class="btn btn-blue">
                                Download Import Template
                            </a>
                            <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
=======
    <x-modals.dispose />
    <x-modals.transfer :models="$locations" />
    <x-modals.delete />
    <x-modals.import />
>>>>>>> b776c015070e13c44fce8b97db9ea976cfd4cf48

    {{-- This is the Modal for Bulk Disposal {SC} --}}
    <div class="modal fade bd-example-modal-lg" id="bulkDisposalModal" tabindex="-1" role="dialog"
         aria-labelledby="bulkDisposalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDisposalModalLabel">Bulk Disposal Assets - Upload</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('assets.bulk.disposal')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Select "import disposals" to bulk dispose of Assets on the system</p>
                        <input class="form-control shadow-sm"
                               type="file" placeholder="Upload here" name="csv" accept=".csv" id="disposeAssets" required>
                    </div>
                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-danger" type="button">
                            Import Disposals
                        </button>

                        @csrf
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- This is the Modal for Bulk Transfers {SC} --}}
    <div class="modal fade bd-example-modal-lg" id="bulkTransferModal" tabindex="-1" role="dialog"
         aria-labelledby="bulkTransferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkTransferModalLabel">Bulk Transfer Assets - Upload</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('assets.bulk.transfer')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Select "import transfers" to bulk transfer assets to different locations on the system</p>
                        <input class="form-control shadow-sm"
                               type="file" placeholder="Upload here" name="csv" accept=".csv" id="transferAssets" required>
                    </div>
                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-danger" type="button">
                            Import Transfers
                        </button>
                    @csrf
                    </div>
                </form>
            </div>
        </div>
    </div>



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
