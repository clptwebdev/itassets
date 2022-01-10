@extends('layouts.app')

@section('title', 'Create New Asset')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('assets.store')" >
        <x-wrappers.nav title="Add New Asset(s)" >
            <x-buttons.return :route="route('assets.index')" > Assets</x-buttons.return >
            <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm" ><i
                    class="fas fa-question fa-sm text-dark-50" ></i > Asset Help</a >
            <x-buttons.submit >Save</x-buttons.submit >
        </x-wrappers.nav >
        <section >
            <p class="mb-4" >Adding a new Asset to the asset management system. Enter the following information and
                             click
                             the 'Save' button. Or click the 'Back' button to return the Assets page.
            </p >
            <div class="row row-eq-height container m-auto" >
                <div class="col-12" >
                    <div class="card shadow h-100" >
                        <div class="card-body" >
                            <x-form.errors />
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
                                            <div class="form-group position-relative" >
                                                <label for="findModel" >Asset Model</label >
                                                <input type="hidden" id="asset_model" name="asset_model"
                                                       class="form-control mb-3" readonly >
                                                <input class="form-control" type="text" name="find_model" id="findModel"
                                                       value="" autocomplete="off" placeholder="Search for Model" >
                                                <div id="modelResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;" >
                                                    <ul id="modelSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                                <small class="form-text text-muted" >Can't find the Model your after?
                                                    <a href="#" data-toggle="modal" data-target="#newModel" >
                                                        Click Here</a >to create one.</small >
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="name" formAttributes="required" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="asset_tag" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="serial_no" formAttributes="required" />
                                            </div >
                                            @if(old('asset_model') !== null && $model = \App\Models\AssetModel::find(old('asset_model')))
                                                <div id="additional-fields" >
                                                    @if($model->fieldset()->exists() && $model->fieldset->fields()->exists())
                                                        @foreach($model->fieldset->fields as $field)

                                                            <div class="form-group" >
                                                                <label
                                                                    for="{{str_replace(' ', '_', strtolower($field->name))}}" >{{$field->name}}</label >
                                                                @switch($field->type)
                                                                    @case('Text'):
                                                                    <x-form.input :name="$field->name" />
                                                                    @break
                                                                    @case('Textarea')
                                                                    <x-form.textarea :name="$field->name"
                                                                                     formAttributes=" cols=' 30' rows='10' " />
                                                                    @break
                                                                    @case('Select')
                                                                    <?php $array = explode("\r\n", $field->value);?>
                                                                    <x-form.select :name="$field->name"
                                                                                   :models="$array" />
                                                                    @break
                                                                    @case('Checkbox')
                                                                    <?php $array = explode("\r\n", $field->value);?>
                                                                    <?php $values = explode(",", old(str_replace(' ', '_', strtolower($field->name))));?>
                                                                    <x-form.checkbox :models="$array"
                                                                                     :name="$field->name"
                                                                                     :checked="$values" />
                                                                    @break
                                                                    @default
                                                                    <x-form.input :name="$field->name" />
                                                                @endswitch
                                                            </div >
                                                        @endforeach
                                                    @endif
                                                </div >
                                            @else
                                                <div id="additional-fields" style="display: none;" >
                                                    <span class="text-warning" >No Additional Fields Required</span >
                                                </div >
                                            @endif
                                        </div >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="modelInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Asset Model</div >
                                                <div class="model_image p-4" >
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/device-image.svg') }}" width="100%"
                                                         alt="Select Profile Picture" >
                                                </div >
                                                <div class="model_no py-2 px-4" >
                                                    Manufacturer:
                                                </div >
                                                <div class="model_no py-2 px-4" >
                                                    Model No:
                                                </div >
                                                <div class="model_no py-2 px-4" >
                                                    Depreication:
                                                </div >
                                                <div class="model_no py-2 px-4" >
                                                    Additional Fieldsets:
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div >
                                <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel"
                                     aria-labelledby="purchase-tab" >
                                    <div class="row" >
                                        <div class="col-12 col-sm-6 p-4 mb-3" >
                                            <h3 class="h6 text-center mb-3" >Purchase Information</h3 >
                                            <div class="form-group" >
                                                <x-form.input name="order_no" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.date name="purchased_date" formAttributes="required" />
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="purchased_cost" formAttributes="required" />
                                                <div class="form-check mt-2 ml-1" >
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="donated" id="donated" >
                                                    <label class="form-check-label" for="donated" >
                                                        Donated
                                                    </label >
                                                </div >
                                            </div >
                                            <div class="form-group position-relative" >
                                                <label for="findSupplier" >Supplier</label >
                                                <input type="hidden" id="supplier_id" name="supplier_id"
                                                       class="form-control mb-3" readonly >
                                                <input class="form-control" type="text" name="find_supplier"
                                                       id="findSupplier" value="" placeholder="Search for Supplier" >
                                                <div id="supplierResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;" >
                                                    <ul id="supplierSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                            </div >
                                            <div class="form-group" >
                                                <x-form.input name="warranty" />
                                            </div >
                                        </div >

                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="supplierInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Supplier Name</div >
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle" >
                                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}"
                                                         height="150px"
                                                         alt="Select Profile Picture" >
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Address
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Website
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Email
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Notes
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div >
                                <div class="tab-pane fade p-2 pt-4" id="location" role="tabpanel"
                                     aria-labelledby="location-tab" >
                                    <div class="row" >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div class="form-group position-relative" >
                                                <label for="findLocation" >Location</label >
                                                <input type="hidden" id="location_id" name="location_id"
                                                       class="form-control mb-3" readonly >
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="" placeholder="Search for Location" >
                                                <div id="locationResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;" >
                                                    <ul id="locationSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                            </div >

                                            <div class="form-group" >
                                                <x-form.input name="room" />
                                            </div >
                                        </div >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="locationInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Location Name</div >
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle" >
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/location-image.svg') }}"
                                                         height="200px"
                                                         alt="Select Profile Picture" >
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Address
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Website
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Email
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Notes
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div >
                                <div class="tab-pane fade p-2 pt-4" id="attributes" role="tabpanel"
                                     aria-labelledby="attributes-tab" >
                                    <div class="row" >
                                        <div class="col-12 p-4 mb-3" >

                                            <div class="form-group" >
                                                <x-form.date name="audit_date" />
                                            </div >
                                            <div id="categories" class="form-control h-auto p-4 mb-3 bg-light" >
                                                <h4 class="h6 mb-4 text-center" >Categories</h4 >
                                                <div class="position-relative" >
                                                    @csrf
                                                    <input type="hidden" id="category_id" name="category"
                                                           class="form-control mb-3" readonly >
                                                    <input class="form-control" type="text" name="find_category"
                                                           id="findCategory" value=""
                                                           placeholder="Search for Categories" >
                                                    <div id="categoryResults"
                                                         class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                         style="visibility: hidden; z-index: 2;" >
                                                        <ul id="categorySelect" >
                                                            <li >Nothing to Return</li >
                                                        </ul >
                                                    </div >
                                                    <div id="selectedCategories" class="row mt-3 mb-2 p-2" >

                                                    </div >
                                                </div >
                                            </div >
                                            <div class="form-row" >
                                                <x-form.select name="status_id" :models="$statuses" />
                                            </div >
                                        </div >
                                    </div >
                                </div >
                            </div >
                        </div >
                    </div >
                </div >
            </div >
        </section >
    </x-form.layout >

@endsection

@section('modals')
    <x-models.create :mans="$mans" :depreciation="$depreciation" :fieldsets="$fieldsets" />
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/assets.js')}}" ></script >
@endsection
