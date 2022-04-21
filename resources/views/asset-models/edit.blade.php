@extends('layouts.app')

@section('title', 'Edit '.$assetModel->name)

@section('css')

@endsection

@section('content')
    <form action="{{ route('asset-models.update', $assetModel) }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Asset Model</h1>

            <div>
                @can('viewAny' , \App\Models\AssetModel::class)
                    <a href="{{ route('asset-models.index') }}"
                       class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Asset Models</a>
                @endcan

                <a href="{{ route('documentation.index')."#collapseFithteenAssetModels"}}"
                   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                        class="fas fa-question fa-sm text-dark-50"></i> need Help?</a>
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
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" value="{{ $assetModel->name }}">
                            </div>
                            <div class="form-group">
                                <label for="manufacturer">Manufacturer:</label>
                                <select
                                    class="form-control mb-3 <?php if ($errors->has('manufacturer_id')){?>border-danger<?php }?>"
                                    name="manufacturer_id" id="manufacturer_id" required>
                                    <?php $mans = App\Models\Manufacturer::all();?>
                                    <option value="0">Please select a Manufacturer</option>
                                    @foreach($mans as $man)
                                        <option value="{{$man->id}}"
                                                @if($man->id == $assetModel->manufacturer_id)selected
                                            @endif
                                        >{{ $man->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="model_no">Model No:</label>
                                <input type="text"
                                       class="form-control mb-3 <?php if ($errors->has('model_no')){?>border-danger<?php }?>"
                                       name="model_no" id="model_no" value="{{$assetModel->model_no }}" required>
                            </div>
                            <div class="form-group">
                                <label for="depreciation_id">Depreciation</label>
                                <select
                                    class="form-control <?php if ($errors->has('depreciation_id')){?>border-danger<?php }?>"
                                    name="depreciation_id" id="depreciation_id" required>
                                    <option value="0" @if($assetModel->depreciation_id == 0){{ 'selected'}}@endif>No
                                                                                                                  Depreciation
                                                                                                                  Set
                                    </option>
                                    @foreach($depreciation as $dep)
                                        <option
                                            value="{{ $dep->id}}" @if($assetModel->depreciation_id == $dep->id){{ 'selected'}}@endif>{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="eol">EOL (End of Life) Months</label>
                                <input type="text" class="form-control" name="eol" id="eol"
                                       value="{{ $assetModel->eol }}">
                            </div>
                            <div class="form-group">
                                <label for="fieldset_id">Additional Fieldsets</label>
                                <select class="form-control" name="fieldset_id" id="fieldset_id">
                                    <option value="0" @if($assetModel->fieldset_id == 0){{ 'selected'}}@endif>No
                                                                                                              Additional
                                                                                                              Fieldsets
                                                                                                              Required
                                    </option>
                                    @foreach($fieldsets as $fieldset)
                                        <option
                                            value="{{ $fieldset->id }}" @if($assetModel->fieldset_id == $fieldset->id){{ 'selected'}}@endif>{{ $fieldset->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea type="text" class="form-control" rows="10" name="notes"
                                          id="notes">{{ $assetModel->notes }}</textarea>
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
                                    <img id="profileImage"
                                         src="{{ asset($assetModel->photo->path ?? 'images/svg/device-image.svg') }}"
                                         width="100%" alt="Select Profile Picture" data-bs-toggle="modal"
                                         data-bs-target="#imgModal">
                                    <input type="hidden" id="photo_id" name="photo_id"
                                           value="{{ $assetModel->photoid}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-admin.assetModels.details/>
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
