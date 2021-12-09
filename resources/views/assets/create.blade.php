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

                            <ul class="nav nav-tabs">
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
                                                <input type="hidden" id="asset_model" name="asset_model" class="form-control mb-3" disabled>
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
                                                <input type="hidden" id="supplier_id" name="supplier_id" class="form-control mb-3" disabled>
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
                                                <input type="hidden" id="location_id" name="location_id" class="form-control mb-3" disabled>
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
                                                    <input type="hidden" id="category_id" name="category" class="form-control mb-3" disabled>
                                                    <input class="form-control" type="text" name="find_category" id="findCategory" value="cateogrtrtrt" placeholder="Search for Categories">
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
    <div class="modal fade" id="newModel" tabindex="-1" role="dialog" aria-labelledby="newModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="newModelLabel">Create a New Asset Modal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text"
                        class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                        name="name" id="name" placeholder="">
                </div>
                <div class="form-group">
                    <label for="manufacturer">Manufacturer:</label>
                    <select class="form-control mb-3 <?php if ($errors->has('manufacturer_id')){?>border-danger<?php }?>"
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
                    <input type="text" class="form-control" name="eol" id="eol"
                        placeholder="36">
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
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function getFields(value) {
            $.ajax({
                url: `/assets/${value}/model`,
                success: function (data) {
                    document.getElementById("additional-fields").innerHTML = data;
                    document.getElementById("additional-fields").style.display = "block";
                },
                error: function () {
                    document.getElementById("additional-fields").innerHTML = "";
                    document.getElementById("additional-fields").style.display = "none";
                },
            });
        }

        //Search Categories
        const categorySearch = document.querySelector('#findCategory');
        const categoryResults = document.querySelector('#categoryResults');
        const categorySelect = document.querySelector('#categorySelect');

        categorySearch.addEventListener('input', function(e){
            let value = e.target.value;
            if (value.length > 2) {
                const xhttp = new XMLHttpRequest();

                xhttp.onload = function(){
                    categoryResults.innerHTML = xhttp.responseText;
                    categoryResults.style.visibility = "visible";
                    initItems();
                }

                xhttp.open("POST", "/search/category/");
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`search=${value}`); 
            }
        });

        document.addEventListener('click', function(e){
            //If the click is outside of the Search Results or the input then hide the results dropdown
            if (!categorySearch.contains(event.target) && !categoryResults.contains(event.target)) {
                categoryResults.style.visibility = "hidden";
            }            
        });

        function initItems(){
            //Gets all of the list items and adds an event listener to them
            //This has to be re-initialised everytime a result set is returned.
            document.querySelector('#categoryResults').querySelectorAll('li').forEach(function(item) {
                item.addEventListener('click', function() {
                    //Get the information required
                    let name = this.getAttribute('data-name');
                    let id = this.getAttribute('data-id');
                    //Select the Elements
                    const cats = document.querySelector('#category_id');
                    const elements = document.querySelector('#selectedCategories');
                    const array = cats.value.split(',');
                    //Check and see if it already exists
                    const index = array.indexOf(id);
                    if (index == -1) {
                        if(cats.value != ''){ cats.value += ','+id}else{ cats.value = id;}
                        let html = `<div id="cat${id}" class="p-2 col-4">
                                        <div class="border border-gray shadow bg-white p-2 rounded d-flex justify-content-between align-items-center">
                                            <span>${name}</span> 
                                            <i class="fas fa-times ml-4 text-danger pointer" data-name="${id}" onclick="javascript:removeCategory(this);"></i>
                                        </div>
                                    </div>`;
                        elements.insertAdjacentHTML('beforeend', html);
                        categoryResults.style.visibility = "hidden";
                        document.querySelector('#findCategory').value = '';
                    }
                })
            })
        }

        function removeCategory(element){
            const id = element.dataset.name;
            const div = document.querySelector('#cat'+id);
            const cats = document.querySelector('#category_id');
            //Split the String by (,) and put them into an array
            const array = cats.value.split(',');
            //Find the index of the element you would like to remove
            const index = array.indexOf(id);
            console.log(index)
            if (index > -1) {
                //If found remove the index from the array
                array.splice(index, 1);
            }
            //Join the Array (Back to String). join() is empty so by default seperates by a comma
            div.remove();
            cats.value = array.join();
        }

        //Search for the Model
        const modelSearch = document.querySelector('#findModel');
        const modelResults = document.querySelector('#modelResults');

        modelSearch.addEventListener('input', function(e){
            let value = e.target.value;
            if (value.length > 2) {
                const xhttp = new XMLHttpRequest();

                xhttp.onload = function(){
                    modelResults.innerHTML = xhttp.responseText;
                    modelResults.style.visibility = "visible";
                    initModelItems();
                    
                }

                xhttp.open("POST", "/search/models/");
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`search=${value}`); 
            }
        });

        function initModelItems(){
            //Gets all of the list items and adds an event listener to them
            //This has to be re-initialised everytime a result set is returned.
            document.querySelector('#modelResults').querySelectorAll('li').forEach(function(item) {
                item.addEventListener('click', function() {
                    //Get the information required
                    let name = this.getAttribute('data-name');
                    let id = this.getAttribute('data-id');
                    //Select the Elements
                    const cats = document.querySelector('#asset_model');
                    cats.value = id;
                    modelResults.style.visibility = "hidden";
                    document.querySelector('#findModel').value = name;
                    getFields(id);
                    getInfo(id);
                })
            })
        }

        const modelInfo = document.querySelector('#modelInfo');

        function getInfo(id){
            const xhttp = new XMLHttpRequest;
            xhttp.onload = function(){
                modelInfo.innerHTML = xhttp.responseText;
            }

            xhttp.open("POST", "/model/preview/");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`id=${id}`); 
        
        }

        //Search for the Supplier
        const supplierSearch = document.querySelector('#findSupplier');
        const supplierResults = document.querySelector('#supplierResults');

        supplierSearch.addEventListener('input', function(e){
            let value = e.target.value;
            if (value.length > 2) {
                const xhttp = new XMLHttpRequest();

                xhttp.onload = function(){
                    supplierResults.innerHTML = xhttp.responseText;
                    supplierResults.style.visibility = "visible";
                    initSupplierItems();
                    
                }

                xhttp.open("POST", "/search/suppliers/");
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`search=${value}`); 
            }
        });

        function initSupplierItems(){
            //Gets all of the list items and adds an event listener to them
            //This has to be re-initialised everytime a result set is returned.
            document.querySelector('#supplierResults').querySelectorAll('li').forEach(function(item) {
                item.addEventListener('click', function() {
                    //Get the information required
                    let name = this.getAttribute('data-name');
                    let id = this.getAttribute('data-id');
                    //Select the Elements
                    const cats = document.querySelector('#supplier_id');
                    cats.value = id;
                    supplierResults.style.visibility = "hidden";
                    document.querySelector('#findSupplier').value = name;
                    getSupplierInfo(id);
                })
            })
        }

        const supplierInfo = document.querySelector('#supplierInfo');

        function getSupplierInfo(id){
            const xhttp = new XMLHttpRequest;
            xhttp.onload = function(){
                supplierInfo.innerHTML = xhttp.responseText;
            }

            xhttp.open("POST", "/supplier/preview/");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`id=${id}`); 
        
        }

        //Search for the Location
        const locationSearch = document.querySelector('#findLocation');
        const locationResults = document.querySelector('#locationResults');

        locationSearch.addEventListener('input', function(e){
            let value = e.target.value;
            if (value.length > 2) {
                const xhttp = new XMLHttpRequest();

                xhttp.onload = function(){
                    locationResults.innerHTML = xhttp.responseText;
                    locationResults.style.visibility = "visible";
                    initLocationItems();
                    
                }

                xhttp.open("POST", "/search/locations/");
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`search=${value}`); 
            }
        });

        function initLocationItems(){
            //Gets all of the list items and adds an event listener to them
            //This has to be re-initialised everytime a result set is returned.
            document.querySelector('#locationResults').querySelectorAll('li').forEach(function(item) {
                item.addEventListener('click', function() {
                    //Get the information required
                    let name = this.getAttribute('data-name');
                    let id = this.getAttribute('data-id');
                    //Select the Elements
                    const cats = document.querySelector('#location_id');
                    cats.value = id;
                    locationResults.style.visibility = "hidden";
                    document.querySelector('#findLocation').value = name;
                    getLocationInfo(id);
                })
            })
        }

        const locationInfo = document.querySelector('#locationInfo');

        function getLocationInfo(id){
            const xhttp = new XMLHttpRequest;
            xhttp.onload = function(){
                alert(xhttp.responseText);
                locationInfo.innerHTML = xhttp.responseText;
            }

            xhttp.open("POST", "/location/preview/");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`id=${id}`); 
        
        }

        const button = document.querySelector('#submitButton');

        const name = document.querySelector('[name="name"]');

        function sendData(){
            console.log('sending data');

        }
    </script>
@endsection
