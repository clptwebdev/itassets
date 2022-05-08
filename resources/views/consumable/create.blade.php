@extends('layouts.app')

@section('title', 'Create New Consumable')

@section('css')

@endsection

@section('content')
    <form action="{{ route('consumables.store') }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Consumables</h1>

            <div>
                <a href="{{ route('consumables.index') }}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Consumables</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Consumable to the asset management system. Enter in the following information
                            and
                            click the 'Save' button. Or click the 'Back' button
                            to return the Consumables page. </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" placeholder="Consumables Name">
                            </div>
                            <div class="form-group">
                                <label for="serial_no">Serial_no</label>
                                <input type="text"
                                       class="form-control mb-3 <?php if ($errors->has('serial_no')){?>border-danger<?php }?>"
                                       name="serial_no" id="serial_no">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="order_no">Order_no</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('order_no')) {?>border-danger<?php }?>"
                                           id="order_no" name="order_no" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="purchased_cost">Purchased Cost</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('purchase_cost')) {?>border-danger<?php }?>"
                                           id="purchased_cost" name="purchased_cost" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="purchased_date">Purchased Date</label>
                                    <input type="date"
                                           class="form-control <?php if ($errors->has('purchased_date')) {?>border-danger<?php }?>"
                                           id="purchased_date" name="purchased_date" required>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">

                                    <label for="suppliers">Supplier</label>
                                    <select type="text"
                                            class="form-control <?php if ($errors->has('supplier_id')) {?>border-danger<?php }?>"
                                            id="supplier_id" name="supplier_id" required>
                                        <option value="0" @if(old('supplier_id') == 0){{'selected'}}@endif>No Supplier
                                        </option>
                                        @foreach($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id){{'selected'}}@endif>{{ $supplier->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select
                                        class="form-control <?php if ($errors->has('status_id')) {?>border-danger<?php }?>"
                                        id="status_id" name="status_id">
                                        <option value="0" @if(old('status_id') == 0){{'selected'}}@endif>Unset</option>
                                        @foreach($statuses as $status)
                                            <option
                                                value="{{ $status->id }}" @if(old('status_id') == $status->id){{'selected'}}@endif>{{ $status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <h4 class="h6 text-left pb-0">Categories</h4>
                            <div id="categories" class="form-control h-auto p-4 mb-3 rounded">

                                @foreach($categories as $category)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                               name="category[]" id="category{{$category->id}}">
                                        <label class="form-check-label"
                                               for="category{{$category->id}}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Consumable Image</h4>
                                    <img id="profileImage" onclick='getPhotoPage(1)'
                                         src="{{ asset('images/svg/consumables-image.svg') }}" width="100%"
                                         alt="Select Profile Picture">
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <label for=" school location">Location</label>
                                <select
                                    class="form-control <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                    id="location_id" name="location_id" required>
                                    <option value="0" @if(old('location_id') == 0){{'selected'}}@endif>Unallocated
                                    </option>
                                    @foreach($locations as $location)
                                        <option
                                            value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="warranty">Warranty</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('warranty')) {?>border-danger<?php }?>"
                                       id="warranty" name="warranty">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="Warranty">Manufacturer</label>
                                <select
                                    class="form-control <?php if ($errors->has('manufacturer')) {?>border-danger<?php }?>"
                                    id="manufacturer_id" name="manufacturer_id">
                                    <option value="0" @if(old('manufacturer_id') == 0){{'selected'}}@endif>Unallocated
                                    </option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option
                                            value="{{$manufacturer->id}}" @if(old('manufacturer_id') == $manufacturer->id){{'selected'}}@endif>{{$manufacturer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </form>
@endsection

@section('modals')
    <x-modals.photo-upload/>
    <x-modals.photo-upload-form/>
@endsection

@section('js')
    <script src="{{asset('js/photo.js')}}"></script>
@endsection
