@extends('layouts.app')

@section('title', 'Create New Asset')

@section('css')

@endsection

@section('content')
    <form action="{{ route('assets.store')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Asset</h1>

            <div>
                <a href="{{ route('assets.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-dark-50"></i> Back to Assets</a>

                <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
                   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                        class="fas fa-question fa-sm text-dark-50"></i> Asset Help</a>

                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-dark-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter in the following information and
                click
                the 'Save' button. Or click the 'Back' button
                to return the Assets page.
            </p>
            <div class="row row-eq-height container m-auto">
                <div class="col-12">
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

                            <ul id="tab-bar" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="purchase-tab" data-toggle="tab" href="#purchase" role="tab" aria-controls="home" aria-selected="true">Purchase Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="home" aria-selected="true">Location Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributes" role="tab" aria-controls="home" aria-selected="true">Attributes</a>
                                </li>
                            </ul>

                            @csrf
                            <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                                <div class="tab-pane fade show p-2 pt-4 active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3">
                                            <div class="form-group position-relative">
                                                <label for="findModel">Asset Model</label>
                                                <input type="hidden" id="asset_model" name="asset_model" class="form-control mb-3" readonly>
                                                <input class="form-control" type="text" name="find_model" id="findModel" value="" autocomplete="off" placeholder="Search for Model">
                                                <div id="modelResults" class="w-100 h-auto mb-5 d-block search-modal position-absolute" style="visibility: hidden; z-index: 2;">
                                                    <ul id="modelSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                                <small class="form-text text-muted">Can't find the Model your after?  
                                                    <a href="#" data-toggle="modal" data-target="#newModel">Click Here</a> to create one.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="asset_tag">Asset Name</label>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('name')) {?> border border-danger<?php }?>"
                                                    name="name"
                                                    id="name" placeholder="" value="{{ old('name')}}" required>
                                            </div>
        
                                            <div class="form-group">
                                                <label for="asset_tag">Asset Tag Number</label>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('asset_tag')) {?> border border-danger<?php }?>"
                                                    name="asset_tag"
                                                    id="asset_tag" placeholder="" value="{{ old('asset_tag')}}">
                                            </div>
        
                                            <div class="form-group">
                                                <label for="serial_no">Serial Number</label><span class="text-danger">*</span>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('serial_no')) {?>border border-danger<?php }?>"
                                                    name="serial_no"
                                                    id="serial_no" placeholder="" value="{{ old('serial_no')}}">
                                            </div>
                                            @if(old('asset_model') !== null && $model = \App\Models\AssetModel::find(old('asset_model')))
                                            <div id="additional-fields">
                                                @if($model->fieldset()->exists() && $model->fieldset->fields()->exists())
                                                @foreach($model->fieldset->fields as $field)

                                                    <div class="form-group">
                                                        <label
                                                            for="{{str_replace(' ', '_', strtolower($field->name))}}">{{$field->name}}</label>
                                                        @switch($field->type)
                                                            @case('Text'):
                                                            <input type="text"
                                                                class="form-control
                                                        <?php if ($errors->has(str_replace(' ', '_', strtolower($field->name)))) {?>border border-danger<?php }?>"
                                                                name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                value="{{ old(str_replace(' ', '_', strtolower($field->name)))}}"
                                                            >
                                                            @break
                                                            @case('Textarea')
                                                            <textarea
                                                                name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                cols="30"
                                                                rows="10"
                                                                class="form-contol
                                                    <?php if ($errors->has(str_replace(' ', '_', strtolower($field->name)))) {?>border-danger<?php }?>">{{ old(str_replace(' ', '_', strtolower($field->name)))}}
                                                </textarea>
                                                            @break
                                                            @case('Select')
                                                            <?php $array = explode("\r\n", $field->value);?>
                                                            <select
                                                                name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                class="form-control <?php if ($errors->has(str_replace(' ', '_', strtolower($field->name)))) {?>border-danger<?php }?>">
                                                                @foreach($array as $id=>$key)
                                                                    <option
                                                                        value="{{ $key }}" @if(old(str_replace(' ', '_', strtolower($field->name))) == $key){{ 'selected'}}@endif>{{ $key }}</option>
                                                                @endforeach
                                                            </select>
                                                            @break
                                                            @case('Checkbox')
                                                            <?php $array = explode("\r\n", $field->value);?>
                                                            <?php $values = explode(",", old(str_replace(' ', '_', strtolower($field->name))));?>
                                                            @foreach($array as $id=>$key)
                                                                <br><input type="checkbox"
                                                                        name="{{str_replace(' ', '_', strtolower($field->name))}}[]"
                                                                        value="{{ $key }}"
                                                                @if(in_array($key, $values)){{ 'checked'}}@endif>
                                                                <label>&nbsp;{{ $key }}</label>
                                                            @endforeach
                                                            @break
                                                            @default
                                                            <input type="text"
                                                                class="form-control <?php if ($errors->has(str_replace(' ', '_', strtolower($field->name)))) {?>border-danger<?php }?>"
                                                                name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                placeholder="{{ $field->name }}"
                                                                value="{{ old(str_replace(' ', '_', strtolower($field->name)))}}">
                                                        @endswitch
                                                    </div>
                                                @endforeach
                                                    @endif  
                                            </div>
                                            @else
                                                <div id="additional-fields" style="display: none;">
                                                    <span class="text-warning">No Additional Fields Required</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div  class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="modelInfo" class="bg-light p-4">
                                                <div class="model_title text-center h4 mb-3">Asset Model</div>
                                                <div class="model_image p-4">
                                                    <img id="profileImage" src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                                        alt="Select Profile Picture">
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Manufacturer:
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Model No:
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Depreication:
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Additional Fieldsets:
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel" aria-labelledby="purchase-tab">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 p-4 mb-3">
                                            <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                            <div class="form-group">
                                                <label for="order_no">Order No</label>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('order_no')) {?>border border-danger<?php }?>"
                                                    name="order_no" id="order_no" value="{{ old('order_no')}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="purchased_date">Purchased Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date"
                                                    class="form-control <?php if ($errors->has('purchased_date')) {?>border border-danger<?php }?>"
                                                    name="purchased_date" id="purchased_date"
                                                    value="{{ old('purchased_date') ?? \Carbon\Carbon::now()->format('Y-m-d')}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="purchased_cost">Purchased Cost<span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('purchased_cost')) {?>border border-danger<?php }?>"
                                                    name="purchased_cost" id="purchased_cost"
                                                    value="{{ old('purchased_cost')}}" placeholder="£">
                                                <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" value="1" name="donated" id="donated">
                                                <label class="form-check-label" for="donated">
                                                    Donated
                                                </label>
                                                </div>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="findSupplier">Supplier</label>
                                                <input type="hidden" id="supplier_id" name="supplier_id" class="form-control mb-3" readonly>
                                                <input class="form-control" type="text" name="find_supplier" id="findSupplier" value="" placeholder="Search for Supplier">
                                                <div id="supplierResults" class="w-100 h-auto mb-5 d-block search-modal position-absolute" style="visibility: hidden; z-index: 2;">
                                                    <ul id="supplierSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="purchased_date">Warranty (Months)</label>
                                                <input type="number"
                                                    class="form-control <?php if ($errors->has('warranty')) {?>border border-danger<?php }?>"
                                                    name="warranty" id="warranty" value="{{ old('warranty') ?? 24}}">
                                            </div>
        
                                            
                                        </div>

                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="supplierInfo" class="bg-light p-4">
                                                <div class="model_title text-center h4 mb-3">Supplier Name</div>
                                                <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}" height="150px"
                                                        alt="Select Profile Picture">
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Address
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Website
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Email
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Notes
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-2 pt-4" id="location" role="tabpanel" aria-labelledby="location-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div class="form-group position-relative">
                                                <label for="findLocation">Location</label>
                                                <input type="hidden" id="location_id" name="location_id" class="form-control mb-3" readonly>
                                                <input class="form-control" type="text" name="find_location" id="findLocation" value="" placeholder="Search for Supplier">
                                                <div id="locationResults" class="w-100 h-auto mb-5 d-block search-modal position-absolute" style="visibility: hidden; z-index: 2;">
                                                    <ul id="locationSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="room">Room</label>
                                                <input type="text"
                                                    class="form-control <?php if ($errors->has('room')) {?> border border-danger<?php }?>"
                                                    name="room"
                                                    id="room" placeholder="" value="{{ old('room')}}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="locationInfo" class="bg-light p-4">
                                                <div class="model_title text-center h4 mb-3">Location Name</div>
                                                <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}" height="200px"
                                                        alt="Select Profile Picture">
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Address
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Website
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Email
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Notes
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-2 pt-4" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
                                    <div class="row">
                                        <div class="col-12 p-4 mb-3">

                                            <div class="form-group">
                                                <label for="audit_date">Audit Date</label>
                                                <input type="date"
                                                       class="form-control <?php if ($errors->has('audit_date')) {?>border border-danger<?php }?>"
                                                       name="audit_date" id="audit_date"
                                                       value="{{ old('audit_date') ?? \Carbon\Carbon::now()->addYear()->format('Y-m-d')}}">
                                            </div>
                                            
        
                                            <div id="categories" class="form-control h-auto p-4 mb-3 bg-light">
                                                <h4 class="h6 mb-4 text-center">Categories</h4>
                                                <div class="position-relative">
                                                    @csrf
                                                    <input type="hidden" id="category_id" name="category" class="form-control mb-3" readonly>
                                                    <input class="form-control" type="text" name="find_category" id="findCategory" value="" placeholder="Search for Categories">
                                                    <div id="categoryResults" class="w-100 h-auto mb-5 d-block search-modal position-absolute" style="visibility: hidden; z-index: 2;">
                                                        <ul id="categorySelect">
                                                            <li>Nothing to Return</li>
                                                        </ul>
                                                    </div>
                                                    <div id="selectedCategories" class="row mt-3 mb-2 p-2" >
            
                                                    </div>
                                                </div>
                                                
                                            </div>
        
                                            <div class="form-row">
                                                <label for="status">Current Status</label><span class="text-danger">*</span>
                                                <select type="text"
                                                        class="form-control mb-3 <?php if ($errors->has('status')) {?>border-danger<?php }?>"
                                                        name="status_id" id="status_id" value="Stored">
                                                    <option value="0" @if(old('status_id') == 0){{'selected'}}@endif>Unset</option>
                                                    @foreach($statuses as $status)
                                                        <option
                                                            value="{{ $status->id }}" @if(old('status_id') == $status->id){{'selected'}}@endif>{{ $status->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

@endsection

@section('modals')
    <x-models.create :mans="$mans" :depreciation="$depreciation" :fieldsets="$fieldsets"/>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/assets.js')}}"></script>
@endsection
