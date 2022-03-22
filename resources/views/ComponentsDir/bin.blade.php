@extends('layouts.app')


@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Components | Recycle Bin</h1>
        <div>
            @can('viewAll' , \App\Models\Component::class)
                <a href="{{ route('components.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-trash-alt fa-sm text-white-50"></i> Back to Components</a>
            @endcan
            <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                    class="fas fa-question fa-sm text-dark-50"></i> Recycle Bin Help</a>
            @can('generatePDF', \App\Models\Component::class)
                <form class="d-inline-block" action="{{ route('components.pdf')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ json_encode($components->pluck('id'))}}" name="assets"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report
                    </button>
                </form>
            @endcan
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <section>
        <p class="mb-4">Below are the different Components stored in the management system. Each has
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
                                           id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Component Options:</div>
                                            <a href="{{ route('components.restore', $component->id) }}"
                                               class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$component->id}}"
                                                  action="{{ route('components.remove', $component->id) }}"
                                                  method="POST">
                                                @csrf
                                                @can('delete', $component)
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$component->id}}">Delete</a>
                                                @endcan
                                            </form>
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
                <p>This area can be minimised and will contain a little help on the page that the Component is currently
                   on.</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')

    <x-modals.delete :archive="false"/>
    <x-modals.import/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
@endsection
