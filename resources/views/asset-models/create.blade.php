@extends('layouts.app')

@section('title', 'Add New Asset Model')

@section('css')

@endsection

@section('content')
    <form action="{{ route('asset-models.store') }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Asset Model</h1>

            <div>
                <a href="{{ route('asset-models.index') }}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Asset Models</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section class="mt-4">
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
                                       name="name" id="name" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="manufacturer">Manufacturer:</label>
                                <select
                                    class="form-control mb-3 <?php if ($errors->has('manufacturer_id')){?>border-danger<?php }?>"
                                    name="manufacturer_id" id="manufacturer_id" required>
                                    <option value="0">Please select a Manufacturer</option>
                                    @foreach($mans->sortBy('name') as $man)
                                        <option value="{{$man->id}}">{{ $man->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="model_no">Model No:</label>
                                <input type="text"
                                       class="form-control mb-3 <?php if ($errors->has('model_no')){?>border-danger<?php }?>"
                                       name="model_no" id="model_no" placeholder="#" required>
                            </div>
                            <div class="form-group">
                                <label for="depreciation_id">Depreciation</label>
                                <select
                                    class="form-control <?php if ($errors->has('depreciation_id')){?>border-danger<?php }?>"
                                    name="depreciation_id" id="depreciation_id" required>
                                    <option value="0">No Depreciation Set</option>
                                    @foreach($depreciation as $dep)
                                        <option value="{{ $dep->id}}">{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="eol">EOL (End of Life) Months</label>
                                <input type="text" class="form-control" name="eol" id="eol" placeholder="36">
                            </div>
                            <div class="form-group">
                                <label for="fieldset_id">Additional Fieldsets</label>
                                <select class="form-control" name="fieldset_id" id="fieldset_id">
                                    <option value="0">No Additional Fieldsets Required</option>
                                    @foreach($fieldsets as $fieldset)
                                        <option value="{{ $fieldset->id }}">{{ $fieldset->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea type="text" class="form-control" rows="10" name="notes" id="notes"></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Asset Model Image</h4>
                                    <img id="profileImage" src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                         alt="Select Profile Picture">
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header bg-primary-blue text-white">Information</div>
                <div class="card-body">
                    <p>There are currently X Locations on the System</p>
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
