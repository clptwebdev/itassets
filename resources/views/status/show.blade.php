@extends('layouts.app')

@section('title', 'View '.$status->name)

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Status</h1>
    <div>
        <a href="{{ route('status.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn"><i
                class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm updateBtn"><i
                class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Information regarding {{ $status->name }}, the assets that are currently assigned to the location and any request information.</p>

    <div class="row">
        <div class="col-12 display-4 text-center border border-secondary p-2 mt-4 rounded">
            <i class="{{$status->icon}}" style="color: {{$status->colour}};"></i> {{ $status->name }}
        </div>
    </div>
</section>

<div id="statusAccordian">
    
    <div class="card shadow m-2" style="border-left: 0.25rem solid 666;">
        <div id="asset_header" class="card-header">
            <h6 class="m-0 font-weight-bold pointer d-block w-100" data-toggle="collapse" data-target="#asset_collapse" aria-expanded="true" aria-controls="asset_collapse">Assigned Assets</h6>
        </div>
        <div id="asset_collapse" class="collapse show" aria-labelledby="asset_header" data-parent="#statusAccordian">
            <div class="card-body">
                <table class="table table-striped logs">
                    <thead>
                    <tr>
                        <th class="col-9 col-md-2"><small>Item</small></th>
                        <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                        <th class="col-1 col-md-auto"><small>Tag</small></th>
                        <th class="d-none d-xl-table-cell"><small>Date</small></th>
                        <th class="d-none d-xl-table-cell text-center"><small>Cost</small></th>
                        <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                        <th class="col-auto d-none d-xl-table-cell text-center"><small>Warranty (M)</small></th>
                        <th class="col-auto text-center d-none d-md-table-cell"><small>Audit Due</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th><small>Item</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th><small>Tag</small></th>
                        <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                        <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                        <th class=" d-none d-xl-table-cell text-center"><small>Supplier</small></th>
                        <th class=" d-none d-xl-table-cell text-center"><small>Warranty (M)</small></th>
                        <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($status->assets as $asset)
                        <tr>
                            <td>{{ $assetModel->name ?? 'No Model'}}<br><small
                                    class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                            <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                @if($asset->location->photo()->exists())
                                    <img src="{{ asset($asset->location->photo->path)}}" height="30px"
                                        alt="{{$asset->location->name}}"
                                        title="{{ $asset->location->name ?? 'Unnassigned'}}"/>
                                @else
                                    {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                        .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif
                            </td>
                            <td>{{ $asset->asset_tag }}</td>
                            <td class="d-none d-md-table-cell"
                                data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                            <td class="text-center  d-none d-xl-table-cell">
                                £{{ $asset->purchased_cost }}
                            
                            </td>
                            <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                            @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                            <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                {{ $asset->warranty }} Months

                                <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                    Remaining</small>
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
                                        @case($age < 31) 
                                            <span class="text-warning">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                            <br><small>Audit Due Soon</small>
                                            @break
                                        @default
                                            <span class="text-secondary">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span>
                                            <br><small>Audit due in {{floor($age)}} days</small>
                                    @endswitch
                                @endif
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
                                        <div class="dropdown-header">Asset Options:</div>
                                        <a href="{{ route('assets.show', $asset->id) }}"
                                        class="dropdown-item">View</a>
                                        @can('edit', $asset)
                                            <a href="{{ route('assets.edit', $asset->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $asset)
                                            <form id="form{{$asset->id}}"
                                                action="{{ route('assets.destroy', $asset->id) }}" method="POST"
                                                class="d-block p-0 m-0">
                                                @csrf
                                                @method('DELETE')
                                                <a class="deleteBtn dropdown-item" href="#"
                                                data-id="{{$asset->id}}">Delete</a>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow  m-2" style="border-left: 0.25rem solid 666;">
        <div id="accessories_header" class="card-header">
            <h6 class="m-0 font-weight-bold pointer d-block w-100" data-toggle="collapse" data-target="#accessories_collapse" aria-expanded="true" aria-controls="accessories_collapse">Accessories</h6>
        </div>
        <div id="accessories_collapse" class="collapse" aria-labelledby="accessories_header" data-parent="#statusAccordian">
            <div class="card-body">
                <table class="table table-striped logs">
                    <thead>
                    <tr>
                        <th><small>Name</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th class="text-center"><small>Manufacturers</small></th>
                        <th><small>Date</small></th>
                        <th><small>Cost</small></th>
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
                    @foreach($status->accessory as $accessory)

                        <tr>
                            <td>{{$accessory->name}}
                                <br>
                                <small>{{$accessory->serial_no}}</small>
                            </td>
                            <td class="text-center">
                                @if($accessory->location->photo()->exists())
                                    <img src="{{ asset($accessory->location->photo->path)}}" height="30px" alt="{{$accessory->location->name}}" title="{{ $accessory->location->name ?? 'Unnassigned'}}"/>
                                @else
                                    {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($accessory->location->icon ?? '#666').'">'
                                        .strtoupper(substr($accessory->location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif  
                            </td>
                            <td class="text-center">{{$accessory->manufacturer->name ?? "N/A"}}</td>
                            <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                            <td>£{{$accessory->purchased_cost}}</td>
                            <td>{{$accessory->supplier->name ?? 'N/A'}}</td>
                            <td class="text-center"  style="color: {{$accessory->status->colour}};">
                                <i class="{{$accessory->status->icon}}"></i> {{ $accessory->status->name }}
                            </td>
                            @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                            <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                {{ $accessory->warranty }} Months

                                <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small></td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">accessory Options:</div>
                                        @can('view', $accessory)
                                        <a href="{{ route('accessories.show', $accessory->id) }}" class="dropdown-item">View</a>
                                        @endcan
                                        @can('update', $accessory)
                                            <a href="{{ route('accessories.edit', $accessory->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $accessory)
                                            <form id="form{{$accessory->id}}" action="{{ route('accessories.destroy', $accessory->id) }}" method="POST" class="d-block p-0 m-0">
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
            </div>
        </div>
    </div>

    <div class="card shadow m-2" style="border-left: 0.25rem solid 666;">
        <div id="componentHeader" class="card-header">
            <h6 class="m-0 font-weight-bold pointer d-block w-100" data-toggle="collapse" data-target="#componentCollapse" aria-expanded="true" aria-controls="componentCollapse">Components</h6>
        </div>
        <div id="componentCollapse" class="collapse" aria-labelledby="componentHeader" data-parent="#statusAccordian">
            <div class="card-body">
                <table class="table table-striped logs">
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
                    @foreach($status->component as $component)

                        <tr>
                            <td>{{$component->name}}
                                <br>
                                <small>{{$component->serial_no}}</small>
                            </td>
                            <td class="text-center">
                                @if(isset($component->location->photo->path))
                                    <img src="{{ asset($component->location->photo->path)}}" height="30px" alt="{{$component->location->name}}" title="{{ $component->location->name ?? 'Unnassigned'}}"/>'
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

                                <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Component Options:</div>
                                        <a href="{{ route('components.show', $component->id) }}" class="dropdown-item">View</a>
                                        @can('edit', $component)
                                            <a href="{{ route('components.edit', $component->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $component)
                                            <form id="form{{$component->id}}" action="{{ route('components.destroy', $component->id) }}" method="POST" class="d-block p-0 m-0">
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
            </div>
        </div>
    </div>

    <div class="card shadow m-2" style="border-left: 0.25rem solid 666;">
        <div id="consumable_header" class="card-header">
            <h6 class="m-0 font-weight-bold pointer d-block w-100" data-toggle="collapse" data-target="#consumable_collapse" aria-expanded="true" aria-controls="consumable_collapse">Consumables</h6>
        </div>
        <div id="consumable_collapse" class="collapse " aria-labelledby="consumable_header" data-parent="#statusAccordian">
            <div class="card-body">
                <table class="table table-striped logs">
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
                    @foreach($status->consumable as $consumable)
                        <tr>
                            <td>{{$consumable->name}}
                                <br>
                                <small>{{$consumable->serial_no}}</small>
                            </td>
                            <td class="text-center">
                                @if($consumable->location->photo()->exists())
                                    <img src="{{ asset($consumable->location->photo->path)}}" height="30px" alt="{{$consumable->location->name}}" title="{{ $consumable->location->name ?? 'Unnassigned'}}"/>'
                                @else
                                    {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($consumable->location->icon ?? '#666').'">'
                                        .strtoupper(substr($consumable->location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif    
                            </td>  
                            <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                            <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                            <td>£{{$consumable->purchased_cost}}</td>
                            <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                            <td class="text-center">{{$consumable->status->name ??'N/A'}}</td>
                            @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                            <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                {{ $consumable->warranty }} Months

                                <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Consumable Options:</div>
                                        @can('view', $consumable)
                                        <a href="{{ route('consumables.show', $consumable->id) }}" class="dropdown-item">View</a>
                                        @endcan
                                        @can('update', $consumable)
                                            <a href="{{ route('consumables.edit', $consumable->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $consumable)
                                            <form id="form{{$consumable->id}}" action="{{ route('consumables.destroy', $consumable->id) }}" method="POST" class="d-block p-0 m-0">
                                                @csrf
                                                @method('DELETE')
                                                <a class="deleteBtn dropdown-item" href="#"
                                                data-id="{{$consumable->id}}">Delete</a>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')

<!-- update Modal-->
<div class="modal fade bd-example-modal-lg" id="updateStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCategoryModalLabel">Change Status</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">

                        <p>Please enter the name of your category.</p>
                        <input class="form-control" name="name" id="update_name" type="text" value="{{ $status->name }}">
                    </div>
                    <div class="form-group">
                        <p>Will the assets that have this status be deployable?</p>
                        <input type="radio" id="update_deployable_yes" name="deployable" value="1">
                        <label for="deployable_yes">Yes</label><br>
                        <input type="radio" id="update_deployable_no" name="deployable" value="0">
                        <label for="deployable_no">No</label>
                    </div>
                    <div class="form-group">
                        <label for="colour">Icon Colour</label>
                        <input type="color" name="colour" value="{{ $status->colour}}" id="update_colour">
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <select name="icon" class="form-control" id="update_icon">
                            <option value="far fa-circle" @if($status->icon == "far fa-circle"){{'selected'}}@endif><i class="far fa-circle"></i> Doughnut</option>
                            <option value="fas fa-circle" @if($status->icon == "far fa-circle"){{'selected'}}@endif><i class="fas fa-circle"></i> Circle</option>
                            <option value="fas fa-check" @if($status->icon == "far fa-check"){{'selected'}}@endif><i class="fas fa-check"></i> Tick</option>
                            <option value="fas fa-times" @if($status->icon == "far fa-times"){{'selected'}}@endif><i class="fas fa-times"></i> Times</option>
                            <option value="fas fa-skull-crossbones" @if($status->icon == "far fa-skull-crossbones"){{'selected'}}@endif><i class="fas fa-skull-crossbones"></i> Cross Bones</option>
                            <option value="fas fa-tools" @if($status->icon == "far fa-tools"){{'selected'}}@endif><i class="fas fa-tools"></i> Tools</option>
                        </select>
                    </div>
                    <small class="text-info">**You will be able to assign categories to any assets on the system. These
                        can act as a filter.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeLocationModal" tabindex="-1" role="dialog"
    aria-labelledby="removeLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeLocationModalLabel">Are you sure you want to delete this Location?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="location-id" type="hidden" value="{{ $status->id }}">
                <p>Select "Delete" to remove this location from the system.</p>
                <small class="text-danger">**Warning this is permanent. All assets assigned to this location will become
                    available.</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>

    $('.deleteBtn').click(function() {
        $('#deleteForm').attr('action', $(this).data('route'));
        //showModal
        $('#removeStatusModal').modal('show');
    });

    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $('.updateBtn').click(function(){
        $('#updateStatusModal').modal('show');
    });

    $(document).ready( function () {
        $('table.logs').DataTable({
                "autoWidth": false,
                "pageLength": 10,
            });
    } );
</script>
@endsection