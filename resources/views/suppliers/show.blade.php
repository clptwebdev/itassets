@extends('layouts.app')

@section('title', 'View Supplier')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Supplier</h1>
    <div>
        <a href="{{ route('suppliers.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
        <a href="{{ route('suppliers.destroy', $supplier->id) }}"
            class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm"><i
                class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
        <a href="{{ route('suppliers.edit', $supplier->id)}}"
            class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
        <a href="{{ route('suppliers.showPdf', $supplier->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {!! session('success_message')!!} </div>
@endif

<section>
    <p class="mb-4">Information regarding {{ $supplier->name }}, the assets that are currently assigned to the location
        and any request information.</p>

    <div class="row pl-4 pr-2">
        <div class="col-12 col-sm-4 col-md-3 col-xl-2 bg-white rounded overflow-hidden d-flex align-items-center " style="border: solid 3px #666;">
            @if($supplier->photo()->exists())
            <img src="{{ asset($supplier->photo->path ?? '')}}" width="100%" alt="{{ $supplier->name }}" title="{{ $supplier->name }}">
            @else
            <img src="{{ asset('images/svg/suppliers.svg')}}" width="100%" alt="{{ $supplier->name }}" title="{{ $supplier->name }}">
            @endif
        </div>
        <div class="col-12 col-sm-8 col-md-9 col-xl-10">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid #333  ;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Supplier Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col mr-2">
                            <div class="mb-1">
                                {{ $supplier->name }}<br>
                                <p>{{ $supplier->address_1 }}<br>
                                    @if($supplier->address_2 != "")
                                    {{ $supplier->address_2 }}<br>
                                    @endif
                                    {{ $supplier->city }}<br>
                                    {{ $supplier->postcode }}</p>
                                <p>Tel: {{ $supplier->telephone }}</p>
                                @if($supplier->fax != "")
                                {{ $supplier->fax }}<br>
                                @endif
                                <p>Email: {{ $supplier->email }}</p>
                                <p>URL: {{ $supplier->url }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
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
                        <th class=" d-none d-xl-table-cell text-center"><small>Manufacturer</small></th>
                        <th class=" d-none d-xl-table-cell text-center"><small>Warranty (M)</small></th>
                        <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($supplier->asset as $asset)
                        <tr>
                            <td>{{ $asset->name }}<br>
                                {{ $asset->model->name ?? 'No Model'}}<br><small
                                    class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                            <td class="text-center" style="color: {{$asset->location->icon ?? '#666'}}">{{ $asset->location->name ?? 'Unallocated'}}</td>
                            <td>{{ $asset->asset_tag }}</td>
                            <td class="d-none d-md-table-cell"
                                data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                            <td class="text-center  d-none d-xl-table-cell">
                                £{{ $asset->purchased_cost }}
                            
                            </td>
                            <td class="text-center d-none d-xl-table-cell">{{$asset->model->manufacturer->name ?? "N/A"}}</td>
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
                        <th><small>Date</small></th>
                        <th><small>Cost</small></th>
                        <th><small>Manufacturer</small></th>
                        <th class="text-center"><small>Status</small></th>
                        <th class="text-center"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Manufacturer</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($supplier->accessory as $accessory)

                        <tr>
                            <td>{{$accessory->name}}
                                <br>
                                <small>{{$accessory->serial_no}}</small>
                            </td>
                            <td class="text-center" style="color: {{$accessory->location->icon ?? '#666'}}">{{ $accessory->location->name ?? 'Unallocated'}}</td>
                            <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                            <td>£{{$accessory->purchased_cost}}</td>
                            <td>{{$accessory->manufacturer->name ?? 'N/A'}}</td>
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
                        <th><small>Purchased Date</small></th>
                        <th><small>Purchased Cost</small></th>
                        <th><small>Manufacturer</small></th>
                        <th class="text-center"><small>Status</small></th>
                        <th class="text-center"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th><small>Name</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th><small>Purchased Date</small></th>
                        <th><small>Purchased Cost</small></th>
                        <th><small>Manufacturer</small></th>
                        <th class="text-center"><small>Status</small></th>
                        <th class="text-center"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($supplier->component as $component)

                        <tr>
                            <td>{{$component->name}}
                                <br>
                                <small>{{$component->serial_no}}</small>
                            </td>
                            <td class="text-center" style="color: {{$component->location->icon ?? '#666'}}">{{ $component->location->name ?? 'Unallocated'}}</td>
                            <td>{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                            <td>{{$component->purchased_cost}}</td>
                            <td>{{$component->manufacturer->name ?? 'N/A'}}</td>
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
                        <th><small>Purchased Date</small></th>
                        <th><small>Purchased Cost</small></th>
                        <th><small>Manufacturer</small></th>
                        <th class="text-center"><small>Status</small></th>
                        <th class="text-center"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Manufacturer</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($supplier->consumable as $consumable)
                        <tr>
                            <td>{{$consumable->name}}
                                <br>
                                <small>{{$consumable->serial_no}}</small>
                            </td>
                            <td class="text-center" style="color: {{$consumable->location->icon ?? '#666'}}">{{ $consumable->location->name ?? 'Unallocated'}}</td>
                            <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                            <td>£{{$consumable->purchased_cost}}</td>
                            <td>{{$consumable->manufacturer->name ?? 'N/A'}}</td>
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

    <div class="card shadow m-2" style="border-left: 0.25rem solid 666;">
        <div id="consumable_header" class="card-header">
            <h6 class="m-0 font-weight-bold pointer d-block w-100" data-toggle="collapse" data-target="#miscellaneous_collapse" aria-expanded="true" aria-controls="miscellaneous_collapse">Miscellaneous</h6>
        </div>
        <div id="miscellaneous_collapse" class="collapse " aria-labelledby="miscellaneous_header" data-parent="#statusAccordian">
            <div class="card-body">
                <table class="table table-striped logs">
                    <thead>
                    <tr>
                        <th><small>Name</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th><small>Purchased Date</small></th>
                        <th><small>Purchased Cost</small></th>
                        <th><small>Manufacturer</small></th>
                        <th class="text-center"><small>Status</small></th>
                        <th class="text-center"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Manufacturer</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($supplier->miscellanea as $miscellanea)
                        <tr>
                            <td>{{$miscellanea->name}}
                                <br>
                                <small>{{$miscellanea->serial_no}}</small>
                            </td>
                            <td class="text-center" style="color: {{$miscellanea->location->icon ?? '#666'}}">{{ $miscellanea->location->name ?? 'Unallocated'}}</td>
                            <td>{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
                            <td>£{{$miscellanea->purchased_cost}}</td>
                            <td>{{$miscellanea->manufacturer->name ?? 'N/A'}}</td>
                            <td class="text-center">{{$miscellanea->status->name ??'N/A'}}</td>
                            @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
                            <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                {{ $miscellanea->warranty }} Months

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
                                        @can('view', $miscellanea)
                                        <a href="{{ route('miscellaneous.show', $miscellanea->id) }}" class="dropdown-item">View</a>
                                        @endcan
                                        @can('update', $miscellanea)
                                            <a href="{{ route('miscellaneous.edit', $miscellanea->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $miscellanea)
                                            <form id="form{{$miscellanea->id}}" action="{{ route('miscellaneous.destroy', $miscellanea->id) }}" method="POST" class="d-block p-0 m-0">
                                                @csrf
                                                @method('DELETE')
                                                <a class="deleteBtn dropdown-item" href="#"
                                                data-id="{{$miscellanea->id}}">Delete</a>
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
                <input id="location-id" type="hidden" value="">
                <p>Select "Delete" to remove this location from the system.</p>
                <small class="text-danger">**Warning this is permanent. All assets assigned to this location will become
                    available.</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-coral" type="button" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $('.deleteBtn').click(function() {
        $('#removeModal').modal('show')
    });

    $(document).ready( function () {
        $('table.logs').DataTable({
            "autoWidth": false,
            "pageLength": 10,
        });
    });
</script>
@endsection
