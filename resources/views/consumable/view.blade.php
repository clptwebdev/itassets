@extends('layouts.app')

@section('title', 'View Consumables')

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Consumables</h1>
        <div>
            @can('recycleBin', \App\Models\Consumable::class)
                <a href="{{ route('consumables.bin')}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm">
                    <i class="fas fa-trash-alt fa-sm text-white-50"></i> Recycle Bin
                                                                         ({{ \App\Models\Consumable::onlyTrashed()->count()}}
                                                                         )</a>
            @endcan
            @can('create', \App\Models\Consumable::class)
                <x-buttons.add :route="route('consumables.create')">Consumable(s)</x-buttons.add>
            @endcan
            @can('generatePDF', \App\Models\Consumable::class)
                @if ($consumables->count() == 1)
                    <a href="{{ route('consumables.showPdf', $consumables[0]->id)}}"
                       class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
                @else
                    <form class="d-inline-block" action="{{ route('consumables.pdf')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($consumables->pluck('id'))}}" name="consumables"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                                class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report
                        </button>
                    </form>
                @endif
                @if($consumables->count() >1)
                    <a href="/exportconsumables"
                       class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                            class="fas fa-download fa-sm text-white-50"></i>Export</a>
                @endif
            @endcan
            @can('import', \App\Models\Consumable::class)
                <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Import</a>
            @endcan
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <p class="mb-4">Below are the different Consumables stored in the management system. Each has
                        different options and locations can created, updated, and deleted.</p>
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
                        @foreach($consumables as $consumable)
                            <tr>
                                <td>{{$consumable->name}}
                                    <br>
                                    <small>{{$consumable->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($consumable->location()->exists())
                                        @if($consumable->location->photo()->exists())
                                            <img src="{{ asset($consumable->location->photo->path)}}" height="30px"
                                                 alt="{{$consumable->location->name}}"
                                                 title="{{ $consumable->location->name ?? 'Unnassigned'}}"/>'
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($consumable->location->icon ?? '#666').'">'
                                                .strtoupper(substr($consumable->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                                <td data-sort="{{strtotime($consumable->purchased_date)}}">{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                                <td>£{{$consumable->purchased_cost}}</td>
                                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center">{{$consumable->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $consumable->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Consumable Options:</div>
                                            @can('view', $consumable)
                                                <a href="{{ route('consumables.show', $consumable->id) }}"
                                                   class="dropdown-item">View</a>
                                            @endcan
                                            @can('update', $consumable)
                                                <a href="{{ route('consumables.edit', $consumable->id) }}"
                                                   class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $consumable)
                                                <form id="form{{$consumable->id}}"
                                                      action="{{ route('consumables.destroy', $consumable->id) }}"
                                                      method="POST" class="d-block p-0 m-0">
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
                    <x-paginate :model="$consumables"/>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Consumable</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseTwentyConsumables'}}">here</a> for the
                   Documentation on Consumables on Importing ,Exporting , Adding , Removing!</p>

            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.import/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>

@endsection
