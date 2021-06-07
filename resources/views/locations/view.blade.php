@extends('layouts.app')

@section('css')

@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Locations</h1>
    <div>
        <a href="{{ route('location.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Location</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
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
    <p class="mb-4">Below are different tiles, one for each location stored in the management system. Each tile has different options and locations can created, updated, and deleted.</p>

    <div class="row">
        @foreach($locations as $location)
        <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-4">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$location->icon}};">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="color: {{$location->icon}};">{{ $location->name}}</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('location.edit', $location->id)}}">Edit</a>
                            <a class="dropdown-item" href="#" data-id="{{ $location->id }}">>Delete</a>
                            
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
                                    <img src="{{ $location->photo->path ?? 'null' }}" alt="{{ $location->name}}" width="60px">
                                @else
                                    <i class="fas fa-school fa-2x text-gray-300"></i>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection

@section('modals')

<div class="modal fade" id="removeLocationModal" tabindex="-1" role="dialog"
    aria-labelledby="removeLocationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeLocationLabel">Are you sure you want to delete this Location?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you would like to remove these <strong>Locations</strong> from the system?</p>
                <small class="text-warning">**Warning this is permanent and the Assets assigned to this location will be set to Available.</small>
            </div>
            <div class="modal-footer">
                <form  action="{{ route('location.destroy', ) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')

@endsection