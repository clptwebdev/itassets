@extends('layouts.app')

@section('css')

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manufacturers</h1>
        <div>
            <a href="/manufacturers/create" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Add New Manufacturers</a>
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
        <p class="mb-4">Below are different tiles, one for each manufacturers stored in the management system. Each tile has different manufacturers information that can be created, updated, and deleted.</p>

        <div class="row">
            @foreach($manufacturers as $manufacturer)
                <div class="col-xl-3 col-md-4 mb-4">
                    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$manufacturer->photoId}};">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold">{{ $manufacturer->name}}</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row no-gutters">
                                <div class="col mr-2">
                                    <div class="mb-1">
                                        <p><a href="{{ $manufacturer->supportUrl }}">{{ $manufacturer->supportUrl }}</a></p>
                                        <p>Tel: {{ $manufacturer->supportPhone }}</p>
                                        <p>Email: {{ $manufacturer->supportEmail }}</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <img src="{{$manufacturer->photoId}}" style="max-width: 50px">
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

@endsection

@section('js')

@endsection
