@extends('layouts.app')

@section('title', 'Locations')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Locations</h1>
        <div>
            @can('create', \App\Models\Location::class)
                <x-buttons.add :route="route('location.create')">Location</x-buttons.add>
            @endcan
            @can('viewAny', \App\Models\Location::class)
                <a href="{{ route('location.pdf')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                </a>
                @if($locations->count() >1)
                    <a href="exportlocations" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i>Export
                    </a>
                @endif
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
        <p class="mb-4">Below are different tiles, one for each location stored in the management system. Each tile has
                        different options and locations can created, updated, and deleted.</p>

        <div class="row">
            @foreach($locations as $location)
                <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-4">
                    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$location->icon}};">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold"
                                style="color: {{$location->icon}};">{{ $location->name}}</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('location.show', $location->id)}}">View</a>
                                    <a class="dropdown-item" href="{{ route('location.edit', $location->id)}}">Edit</a>
                                    <form id="form{{$location->id}}"
                                          action="{{ route('location.destroy', $location->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a href="#" class="dropdown-item deleteBtn"
                                           data-id="{{$location->id}}">Delete</a>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row no-gutters">
                                <div class="col mr-2">
                                    <div class="mb-1">
                                        {{ $location->name }}<br>
                                        <p>{{ $location->address_1 }}<br>
                                            {{ $location->city }}<br>
                                            {{ $location->postcode }}</p>
                                        <p>Tel: {{ $location->telephone }}</p>
                                        <p>Email: {{ $location->email }}</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    @if ($location->photo()->exists())
                                        <img src="{{ $location->photo->path ?? 'null' }}" alt="{{ $location->name}}"
                                             width="60px">
                                    @else
                                        <i class="fas fa-school fa-2x text-gray-300"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="row no-gutters border-top border-info pt-4">
                                <div class="col-12">
                                    <table width="100%">
                                        <thead>
                                        <tr>
                                            <th class="text-center"><span
                                                    class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Assets"><i
                                                        class="fas fa-fw fa-tablet-alt"></i></span></th>
                                            <th class="text-center"><span
                                                    class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Accessories"><i class="fas fa-fw fa-keyboard"></i></span>
                                            </th>
                                            <th class="text-center"><span
                                                    class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Components"><i
                                                        class="fas fa-fw fa-hdd"></i></span></th>
                                            <th class="text-center"><span
                                                    class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Consumables"><i class="fas fa-fw fa-tint"></i></span></th>
                                            <th class="text-center"><span
                                                    class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="MIscellaneous"><i class="fas fa-fw fa-question"></i></span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{$location->asset->count() ?? "N/A"}}</td>
                                            <td class="text-center">{{$location->accessory->count() ?? "N/A"}}</td>
                                            <td class="text-center">{{$location->components->count() ?? "N/A"}}</td>
                                            <td class="text-center">{{$location->consumable->count() ?? "N/A"}}</td>
                                            <td class="text-center">{{$location->miscellanea->count() ?? "N/A"}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Locations</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseFiveLocations'}}">here</a> for the
                   Documentation on Locations on Importing ,Exporting , Adding , Removing!</p>

            </div>
        </div>
    </section>

@endsection

@section('modals')
    <x-modals.delete :archive="true"/>

@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
@endsection
