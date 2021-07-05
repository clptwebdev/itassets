@extends('layouts.app')

@section('css')

@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Supplier</h1>
    <div>
        <a href="{{ route('supplier.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
        <a href="{{ route('supplier.edit', $supplier->id) }}"
            class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
        <a href="{{ route('supplier.edit', $supplier->id)}}"
            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
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
    <p class="mb-4">Information regarding {{ $supplier->name }}, the assets that are currently assigned to the location
        and any request information.</p>

    <div class="row">
        <div class="col-12 col-sm-4 col-md-3 col-xl-2">
            <img src="{{ asset($supplier->photo->path ?? '')}}" width="100%" alt="{{ $supplier->name }}"
                title="{{ $supplier->name }}">
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
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')


@endsection
