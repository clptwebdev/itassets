@extends('layouts.app')

@section('title', 'Edit Component')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('components.update', $data->id)" method="PATCH">
        <x-wrappers.nav title="Edit components">
            <x-buttons.return :route="route('components.index')">Components</x-buttons.return>
            <x-buttons.help :route=" route('documentation.index').'#collapseNineComponent'"></x-buttons.help>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>

        <x-form.errors/>
        <section>
            <p class="mb-4">Edit {{ $data->name}}, Component stored in the Apollo Asset Management System. Change
                the information
                and
                click the 'Save' button. Or click the 'Back' button
                to return the Components page.
            </p>
            
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto"">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <x-handlers.alerts />

                            <ul id="tab-bar" class="nav nav-tabs" >
                                <li class="nav-item" >
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                       aria-controls="home" aria-selected="true" >Overview</a >
                                </li >
                                <li class="nav-item" >
                                    <a class="nav-link" id="purchase-tab" data-toggle="tab" href="#purchase" role="tab"
                                       aria-controls="home" aria-selected="true" >Purchase Information</a >
                                </li >
                                <li class="nav-item" >
                                    <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab"
                                       aria-controls="home" aria-selected="true" >Location Information</a >
                                </li >
                                <li class="nav-item" >
                                    <a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributes"
                                       role="tab" aria-controls="home" aria-selected="true" >Attributes</a >
                                </li >
                            </ul >

                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent" >
                                <div class="tab-pane fade show p-2 pt-4 active" id="home" role="tabpanel"
                                     aria-labelledby="home-tab" >
                                    <div class="row" >
                                        <div class="col-12 col-md-6 p-4 mb-3" >
                                            <div class="form-group">
                                                <x-form.input name="name" formAttributes="required" value="{{old('name') ?? $data->name}}" />
                                            </div>
                                            <div class="form-group">
                                                <x-form.select name="manufacturer_id" :models="$manufacturers" selected="{{ $data->manufacturer_id ?? 0}}"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="serial_no" formAttributes="required" value="{{old('serial_no') ?? $data->serial_no}}"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="modelInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Component Image</div >
                                                <div class="model_image p-4" >
                                                    @if($data->photo()->exists() && $data->photo()->exists())
                                                    <img id="profileImage"
                                                            src="{{ asset($data->photo->path) ?? asset('images/svg/device-image.svg') }}" width="100%"
                                                            alt="Select Profile Picture"
                                                            data-toggle="modal" data-target="#imgModal"
                                                            >
                                                    @else
                                                    <img id="profileImage"
                                                            src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                                            alt="Select Profile Picture"
                                                            data-toggle="modal" data-target="#imgModal"
                                                            >
                                                    @endif
                                                    <input type="hidden" id="photo_id" name="photo_id" value="{{ old('photo_id') ?? 0}}">
                                                </div >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel" aria-labelledby="purchase-tab" >
                                    <div class="row" >
                                        <div class="col-12 col-md-6 p-4 mb-3" >
                                            <h3 class="h6 text-center mb-3" >Purchase Information</h3 >
                                            <div class="form-group" >
                                                <x-form.input name="order_no" value="{{old('order_no') ?? $data->order_id}}" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.date name="purchased_date" formAttributes="required" value="{{ \Carbon\Carbon::parse($data->purchased_date)->format('Y-m-d')}}" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="purchased_cost" formAttributes="required" value="{{old('purchased_cost') ?? $data->purchased_cost}}" />
                                            </div >
                                            <div class="form-group position-relative" >
                                                <label for="findSupplier" >Supplier</label >
                                                <input type="hidden" id="supplier_id" name="supplier_id"
                                                        class="form-control mb-3" readonly value="{{old('supplier_id') ?? $data->supplier_id}}" >
                                                <input class="form-control" type="text" name="find_supplier"
                                                        id="findSupplier" value="" value="{{old('find_supplier') ?? $data->supplier->name}}" placeholder="Search for Supplier" autocomplete="off">
                                                <div id="supplierResults"
                                                        class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                        style="visibility: hidden; z-index: 2;" >
                                                    <ul id="supplierSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="warranty" value="{{old('warranty') ?? $data->warranty}}"/>
                                            </div >
                                        </div >
    
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="supplierInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Supplier Name</div >
                                                <div class="model_image p-4 d-flex justify-content-center align-items-middle" >
                                                    @if($data->supplier()->exists() && $data->supplier->photo()->exists())
                                                    <img id="profileImage" src="{{ asset($data->supplier->photo->path) }}"
                                                            height="150px"
                                                            alt="Select Profile Picture" >
                                                    @else
                                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}"
                                                            height="150px"
                                                            alt="Select Profile Picture" >
                                                    @endif
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    @if($data->supplier()->exists())
                                                        {{$data->supplier->address_1 ?? 'Address Line 1'}}, {{$data->supplier->city ?? 'City'}}. {{$data->supplier->postcode, 'Post Code'}}
                                                    @else
                                                    Address
                                                    @endif
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    {{$data->supplier->url ?? 'Website'}}
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    {{$data->supplier->email ?? 'Email'}}
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    {{$data->supplier->notes ?? 'Notes'}}
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div>

                                <div class="tab-pane fade p-2 pt-4" id="location" role="tabpanel"
                                     aria-labelledby="location-tab">
                                    <div class="row" >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div class="form-group position-relative" >
                                                <label for="findLocation" >Location</label >
                                                <input type="hidden" id="location_id" name="location_id"
                                                       class="form-control mb-3" readonly value="{{old('location_id') ?? $data->location_id}}">
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="" autocomplete="off" placeholder="Search for Location" value="{{old('find_location') ?? $data->location->name}}">
                                                <div id="locationResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;" >
                                                    <ul id="locationSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                            </div >
                                        </div >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="locationInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Location Name</div >
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle" >
                                                    @if($data->location()->exists() && $data->location->photo()->exists())
                                                    <img id="profileImage"
                                                         src="{{ asset($data->location->photo->path) }}"
                                                         height="200px"
                                                         alt="Select Profile Picture" >
                                                    @else
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/location-image.svg') }}"
                                                         height="200px"
                                                         alt="Select Profile Picture" >
                                                    @endif
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    @if($data->location()->exists())
                                                        {{ $data->location->address_1}}, {{ $data->location->city}}. {{ $data->location->postcode}},
                                                    @else
                                                        Address
                                                    @endif
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    {{ $data->location->telephone}}
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    {{ $data->location->email}}
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div >

                                <div class="tab-pane fade p-2 pt-4" id="attributes" role="tabpanel"
                                     aria-labelledby="attributes-tab" >
                                    <div class="row" >
                                        <div class="col-12 p-4 mb-3" >
                                            <div id="categories" class="form-control h-auto p-4 mb-3 bg-light" >
                                                <h4 class="h6 mb-4 text-center" >Categories</h4 >
                                                <div class="position-relative" >
                                                    @csrf
                                                    <input type="hidden" id="category_id" name="category"
                                                           class="form-control mb-3" readonly 
                                                           value="{{ implode(",",$data->category->pluck('id')->toArray())}}" >
                                                    <input class="form-control" type="text" name="find_category"
                                                           id="findCategory" value=""
                                                           placeholder="Search for Categories" autocomplete="off" value="{{old('find_category')}}">
                                                    <div id="categoryResults"
                                                         class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                         style="visibility: hidden; z-index: 2;" >
                                                        <ul id="categorySelect" >
                                                            <li >Nothing to Return</li >
                                                        </ul >
                                                    </div >
                                                    <div id="selectedCategories" class="row mt-3 mb-2 p-2" >
                                                        @foreach($data->category as $category)
                                                            <div id="cat{{$category->id}}" class="p-2 col-4" >
                                                                <div
                                                                    class="border border-gray shadow bg-white p-2 rounded d-flex justify-content-between align-items-center" >
                                                                    <span >{{$category->name}}</span >
                                                                    <i class="fas fa-times ml-4 text-danger pointer"
                                                                       data-name="{{$category->id}}"
                                                                       onclick="javascript:removeCategory(this);" ></i >
                                                                </div >
                                                            </div >
                                                        @endforeach
                                                    </div >
                                                </div >
                                            </div >
                                            <div class="form-row mb-3" >
                                                <x-form.select name="status_id" :models="$statuses" selected="{{ $data->status_id}}" />
                                            </div >
                                            <div class="form-row">
                                                <x-form.textarea name="notes" formAttributes="rows='10'" value="{{old('notes') ?? $data->notes}}"/>
                                            </div>
                                        </div >
                                    </div >
                                </div >
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
@endsection
