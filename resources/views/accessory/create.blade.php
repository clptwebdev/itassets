@extends('layouts.app')

@section('title', 'Add New Accessory')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('accessories.store')">
        <x-wrappers.nav title="Add New Accessory">
            <x-buttons.return :route="route('accessories.index')"> Accessories</x-buttons.return>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Adding a new Accessory to the Apollo Asset Management System. Enter the following
                            required information and click the 'Save' button. Or click the 'Back' button
                            to return the accessories page. </p>
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto"
            ">
            <div class="col-12">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <x-handlers.alerts/>

                        <ul id="tab-bar" class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                   aria-controls="home" aria-selected="true">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="purchase-tab" data-bs-toggle="tab" href="#purchase" role="tab"
                                   aria-controls="home" aria-selected="true">Purchase Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                                   aria-controls="home" aria-selected="true">Location Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attributes-tab" data-bs-toggle="tab" href="#attributes"
                                   role="tab" aria-controls="home" aria-selected="true">Attributes</a>
                            </li>
                        </ul>

                        <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                            <div class="tab-pane fade show p-2 pt-4 active" id="home" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-12 col-md-6 p-4 mb-3">
                                        <div class="form-group">
                                            <x-form.input name="name" formAttributes="required"
                                                          value="{{old('name')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.select name="manufacturer_id" :models="$manufacturers"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="model" value="{{old('model')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="asset_tag" value="{{old('asset_tag')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="serial_no" formAttributes="required"
                                                          value="{{old('serial_no')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="modelInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">Asset Model</div>
                                            <div class="model_image p-4">
                                                <img id="profileImage" onclick='getPhotoPage(1)'
                                                     src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                                     alt="Select Profile Picture" data-bs-toggle="modal"
                                                     data-bs-target="#imgModal">
                                                <input type="hidden" id="photo_id" name="photo_id"
                                                       value="{{ old('photo_id') ?? 0}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel"
                                 aria-labelledby="purchase-tab">
                                <div class="row">
                                    <div class="col-12 col-md-6 p-4 mb-3">
                                        <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                        <div class="form-group">
                                            <x-form.input name="order_no" value="{{old('order_no')}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.date name="purchased_date" formAttributes="required"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="purchased_cost" formAttributes="required"
                                                          value="{{old('purchased_cost')}}"/>
                                            <div class="form-check mt-2 ml-1">
                                                <input class="form-check-input" type="checkbox" value="1" name="donated"
                                                       id="donated" @if(old('donated') == 1) {{ 'checked'}} @endif >
                                                <label class="form-check-label" for="donated">
                                                    Donated
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label for="findSupplier">Supplier</label>
                                            <input type="hidden" id="supplier_id" name="supplier_id"
                                                   class="form-control mb-3" readonly value="{{old('supplier_id')}}">
                                            <input class="form-control" type="text" name="find_supplier"
                                                   id="findSupplier" value="" value="{{old('find_supplier')}}"
                                                   placeholder="Search for Supplier" autocomplete="off">
                                            <div id="supplierResults"
                                                 class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                 style="visibility: hidden; z-index: 2;">
                                                <ul id="supplierSelect">
                                                    <li>Nothing to Return</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="warranty" value="{{old('warranty')}}"/>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="supplierInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">Supplier Name</div>
                                            <div
                                                class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                <img id="profileImage" onclick='getPhotoPage(1)'
                                                     src="{{ asset('images/svg/suppliers.svg') }}" height="150px"
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

                            <div class="tab-pane fade p-2 pt-4" id="location" role="tabpanel"
                                 aria-labelledby="location-tab">
                                <div class="row">
                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div class="form-group position-relative">
                                            <label for="findLocation">Location</label>
                                            <input type="hidden" id="location_id" name="location_id"
                                                   class="form-control mb-3" readonly value="{{old('location_id')}}">
                                            <input class="form-control" type="text" name="find_location"
                                                   id="findLocation" value="" autocomplete="off"
                                                   placeholder="Search for Location" value="{{old('find_location')}}">
                                            <div id="locationResults"
                                                 class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                 style="visibility: hidden; z-index: 2;">
                                                <ul id="locationSelect">
                                                    <li>Nothing to Return</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <x-form.input name="room"/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="locationInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">Location Name</div>
                                            <div
                                                class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                <img id="profileImage" onclick='getPhotoPage(1)'
                                                     src="{{ asset('images/svg/location-image.svg') }}" height="200px"
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

                            <div class="tab-pane fade p-2 pt-4" id="attributes" role="tabpanel"
                                 aria-labelledby="attributes-tab">
                                <div class="row">
                                    <div class="col-12 p-4 mb-3">
                                        <div id="categories" class="form-control h-auto p-4 mb-3 bg-light">
                                            <h4 class="h6 mb-4 text-center">Categories</h4>
                                            <div class="position-relative">
                                                @csrf
                                                <input type="hidden" id="category_id" name="category"
                                                       class="form-control mb-3" readonly value="{{old('category')}}">
                                                <input class="form-control" type="text" name="find_category"
                                                       id="findCategory" value="" placeholder="Search for Categories"
                                                       autocomplete="off" value="{{old('find_category')}}">
                                                <div id="categoryResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="categorySelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                                <div id="selectedCategories" class="row mt-3 mb-2 p-2">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mb-3">
                                            <x-form.select name="status_id" :models="$statuses"/>
                                        </div>
                                        <div class="form-row">
                                            <x-form.textarea name="notes" formAttributes="rows='10'"
                                                             value="{{old('notes')}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </x-form.layout>
@endsection

@section('modals')
    <x-modals.image-modal/>
@endsection


@section('js')
    <script type="text/javascript" src="{{ asset('js/accessories.js')}}"></script>
    <script src="{{asset('js/photo.js')}}"></script>
@endsection
