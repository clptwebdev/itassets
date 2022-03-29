@extends('layouts.app')

@section('title', 'Update Furniture, Fixtures and Equipment')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('ffes.update', $ffe->id)">
        <x-wrappers.nav title="Add Furniture, Fixtures and Equipment">
            <x-buttons.return :route="route('ffes.index')">FFE</x-buttons.return>
            <x-buttons.help :route="route('documentation.index').'#collapseTenMiscellaneous'"></x-buttons.help>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>

        <section>
            <p class="mb-4">Update Furnture, Fixtures and Equipment to the Apollo Asset Manager. </p>

            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto">
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
                                                          value="{{old('name') ?? $ffe->name}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.select name="manufacturer_id" :models="$manufacturers" selected="{{$ffe->manufacturer_id}}/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="serial_no" formAttributes="required"
                                                          value="{{old('serial_no') ?? $ffe->serial_no}}"/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="modelInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">FFE Image</div>
                                            <div class="model_image p-4">
                                                @if($ffe->photo()->exists() && $ffe->photo()->exists())
                                                    <img id="profileImage"
                                                         src="{{ asset($ffe->photo->path) ?? asset('images/svg/ffe.svg') }}"
                                                         width="100%" alt="Select Profile Picture"
                                                         data-bs-toggle="modal" data-bs-target="#imgModal">
                                                @else
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/ffe.svg') }}" width="100%"
                                                         alt="Select Profile Picture" data-bs-toggle="modal"
                                                         data-bs-target="#imgModal">
                                                @endif
                                                <input type="hidden" id="photo_id" name="photo_id"
                                                       value="{{ old('photo_id') ?? $ffe->photo_id}}">
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
                                            <x-form.input name="order_no" value="{{old('order_no') ?? $ffe->order_no}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.date name="purchased_date" formAttributes="" value="{{old('purchased_date') ?? $ffe->purchased_date }}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="purchased_cost" formAttributes=""
                                                          value="{{old('purchased_cost') ?? $ffe->purchased_cost}}"/>
                                        </div>
                                        <div class="form-group">
                                            <x-form.input name="depreciation" formAttributes=""
                                                          value="{{old('depreciation') ?? $ffe->depreciation}}"/>
                                        </div>
                                        <div class="form-group position-relative">
                                            <label for="findSupplier">Supplier</label>
                                            <input type="hidden" id="supplier_id" name="supplier_id"
                                                   class="form-control mb-3" readonly value="{{old('supplier_id') ?? $ffe->supplier_id}}">
                                            <input class="form-control" type="text" name="find_supplier"
                                                   id="findSupplier" value="" value="{{old('find_supplier') ?? $ffe->supplier->name ?? ''}}"
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
                                            <x-form.input name="warranty" value="{{old('warranty') ?? $ffe->warranty}}"/>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="supplierInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">{{old('find_supplier') ?? $ffe->supplier->name ?? 'Supplier Name'}}</div>
                                            <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                @if($ffe->supplier()->exists() && $ffe->supplier->photo()->exists())
                                                    <img id="profileImage"
                                                         src="{{ asset($ffe->supplier->photo->path) }}"
                                                         height="150px" alt="Select Profile Picture">
                                                @else
                                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}"
                                                         height="150px" alt="Select Profile Picture">
                                                @endif
                                            </div>
                                            <div class="model_no py-2 px-4 text-center">
                                                @if($accessory->supplier()->exists())
                                                    {{$ffe->supplier->full_address()}}
                                                @else
                                                    Address
                                                @endif
                                            </div>
                                            <div class="model_no py-2 px-4 text-center">
                                                {{$ffe->supplier->url ?? 'Website'}}
                                            </div>
                                            <div class="model_no py-2 px-4 text-center">
                                                {{$ffe->supplier->email ?? 'Email'}}
                                            </div>
                                            <div class="model_no py-2 px-4 text-center">
                                                {{$ffe->supplier->notes ?? 'Notes'}}
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
                                                   class="form-control mb-3" readonly value="{{old('location_id') ?? $ffe->location_id}}">
                                            <input class="form-control" type="text" name="find_location"
                                                   id="findLocation" value="" autocomplete="off"
                                                   placeholder="Search for Location" value="{{old('find_location') ?? $ffe->location->name ?? ''}}">
                                            <div id="locationResults"
                                                 class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                 style="visibility: hidden; z-index: 2;">
                                                <ul id="locationSelect">
                                                    <li>Nothing to Return</li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12 col-md-6 p-4 mb-3 ">
                                        <div id="locationInfo" class="bg-light p-4">
                                            <div class="model_title text-center h4 mb-3">{{ $ffe->location->name }}</div>
                                            <div
                                                class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                <img id="profileImage"
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
