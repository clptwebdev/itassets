@extends('layouts.app')

@section('title', 'Miscellaneous Recycle Bin')

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Miscellaneous | Recycle Bin</h1>
        <div>
            @can('viewAny' , \App\Models\Miscellanea::class)
                <a href="{{ route('miscellaneous.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @endcan

            <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                    class="fas fa-question fa-sm text-dark-50"></i> Recycle Bin Help</a>
        </div>
    </div>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Below are the different miscellaneous stored in the management system. Each has
                        different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div>
                    <table id="usersTable" class="table table-striped">
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
                        @if($miscellaneous->count() != 0)
                            @foreach($miscellaneous as $miscellanea)

                                <tr>
                                    <td>{{$miscellanea->name}}
                                        <br>
                                        <small>{{$miscellanea->serial_no}}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($miscellanea->location->photo()->exists())
                                            <img src="{{ asset($miscellanea->location->photo->path)}}" height="30px"
                                                 alt="{{$miscellanea->location->name}}"
                                                 title="{{ $miscellanea->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($miscellanea->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    </td>
                                    <td class="text-center">{{$miscellanea->manufacturer->name ?? "N/A"}}</td>
                                    <td data-sort="{{ strtotime($miscellanea->purchased_date)}}">{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
                                    <td>£{{$miscellanea->purchased_cost}}</td>
                                    <td>{{$miscellanea->supplier->name ?? 'N/A'}}</td>
                                    <td class="text-center" style="color: {{$miscellanea->status->colour ?? '#666'}};">
                                        <i class="{{$miscellanea->status->icon ?? 'fas fa-circle'}}"></i> {{ $miscellanea->status->name ?? 'N/A' }}
                                    </td>
                                    @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
                                    <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                        {{ $miscellanea->warranty }} Months

                                        <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                            Remaining</small></td>
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
                                                <div class="dropdown-header">miscellanea Options:</div>
                                                @can('delete', $miscellanea)
                                                    <a href="{{ route('miscellaneous.restore', $miscellanea->id) }}"
                                                       class="dropdown-item">Restore</a>
                                                    <form class="d-block" id="form{{$miscellanea->id}}"
                                                          action="{{ route('miscellaneous.remove', $miscellanea->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        <a class="deleteBtn dropdown-item" href="#"
                                                           data-id="{{$miscellanea->id}}">Delete</a>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="10" class="text-center">No Miscellaneous Returned</td>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$miscellaneous"/>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with miscellaneous</h4>
                <p>This area can be minimised and will contain a little help on the page that the miscellanea is
                   currently
                   on.</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.delete :archive="true"> Miscellanea</x-modals.delete>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>


@endsection
