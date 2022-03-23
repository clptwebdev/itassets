@extends('layouts.app')

@section('title', 'Create New Asset')

@section('css')

@endsection

@section('content')
    <x-form.layout id="createNewAsset" :action="route('assets.store')">
        <x-wrappers.nav title="Add New Asset(s)">
            <x-buttons.return :route="route('assets.index')"> Assets</x-buttons.return>
            <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
               class="btn btn-sm  bg-yellow shadow-sm p-2 p-md-1 mr-1"><i
                    class="fas fa-question fa-sm text-dark-50 mr-lg-1"></i><span
                    class="d-none d-lg-inline-block">Help</span></a>
            <button id="createBtn" type="button" class="d-inline-block btn btn-sm btn-green shadow-sm p-2 p-md-1"><i
                    class="fas fa-save fa-sm text-white pr-md-1"></i><span
                    class="d-none d-md-inline-block">Create</span>
            </button>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter the following information and
                            click
                            the 'Save' button. Or click the 'Back' button to return the Assets page. </p>
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <x-handlers.alerts/>


                            <ul id="tab-bar" class="nav nav-tabs">
                                <li class="nav-item active">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#model"
                                       role="tab" aria-controls="home" aria-selected="true">Asset Model</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="purchase-tab" data-bs-toggle="tab" href="#purchase"
                                       role="tab" aria-controls="home" aria-selected="true">Purchase Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="location-tab" data-bs-toggle="tab" href="#location"
                                       role="tab" aria-controls="home" aria-selected="true">Location Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                       aria-controls="home" aria-selected="true">Instances</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="attributes-tab" data-bs-toggle="tab" href="#attributes"
                                       role="tab" aria-controls="home" aria-selected="true">Attributes</a>
                                </li>
                            </ul>
                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent">
                                <div class="tab-pane fade p-2 pt-4" id="home" role="tabpanel"
                                     aria-labelledby="home-tab">
                                    <div class="form-group ">
                                        <div class="row">
                                            <div class="col-3">
                                                Asset Name
                                            </div>
                                            <div class="col-2">
                                                Asset Tag
                                            </div>
                                            <div class="col-3">
                                                Serial No
                                            </div>
                                            <div class="col-3">
                                                Assigned Person or Room
                                            </div>
                                            <div class="col-1">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="assets">


                                        @if(old('asset_tag'))
                                            @php($t = 0)
                                            @for($i=0; $i < count(old('asset_tag')); $i++)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <input type="text"
                                                                   class="form-control @if ($errors->has("name.{$i}"))  {!! 'border border-danger' !!} @endif"
                                                                   name="name[]" tabindex="{{ $t+1}}"
                                                                   value="{{ old('name')[$i]}}"/>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="text"
                                                                   class="form-control @if ($errors->has("asset_tag.{$i}"))  {!! 'border border-danger' !!} @endif"
                                                                   name="asset_tag[]" tabindex="{{$t+1}}"
                                                                   value="{{ old('asset_tag')[$i]}}"/>
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                   class="form-control @if ($errors->has("serial_no.{$i}"))  {!! 'border border-danger' !!} @endif"
                                                                   name="serial_no[]" tabindex="{{$t+1}}"
                                                                   value="{{ old('serial_no')[$i]}}"/>
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text" class="form-control" name="room[]"
                                                                   tabindex="{{$t+1}}" value="{{ old('room')[$i]}}"/>
                                                        </div>
                                                        <div class="col-1">
                                                            <button type="button"
                                                                    class="btn btn-sm btn-coral m-1 asset-remove"><i
                                                                    class="fas fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <input type="text"
                                                               class="form-control @if ($errors->has('name.0'))  {!! 'border border-danger' !!} @endif"
                                                               name="name[]" tabindex="1" value="{{old('name[1]')}}"/>
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="text"
                                                               class="form-control @if ($errors->has('asset_tag.0'))  {!! 'border border-danger' !!} @endif"
                                                               name="asset_tag[]" tabindex="2"/>
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" class="form-control" name="serial_no[]"
                                                               tabindex="3"/>
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" class="form-control" name="room[]"
                                                               tabindex="4"/>
                                                    </div>
                                                    <div class="col-1">
                                                        <button type="button"
                                                                class="btn btn-sm btn-coral m-1 asset-remove"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <button type="button" class="btn btn-sm btn-green add-asset">Add Antoher Instance
                                    </button>
                                </div>
                                <div class="tab-pane fade p-2 pt-4 active show" id="model" role="tabpanel"
                                     aria-labelledby="model-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3">
                                            <div class="form-group position-relative">
                                                <label for="findModel">Asset Model</label>
                                                <input type="hidden" id="asset_model" name="asset_model"
                                                       class="form-control mb-3" readonly
                                                       value="{{old('asset_model')}}">
                                                <input
                                                    class="form-control @if ($errors->has("asset_model"))  {!! 'border border-danger' !!} @endif"
                                                    type="text" name="find_model" id="findModel"
                                                    value="{{old('find_model')}}" autocomplete="off"
                                                    placeholder="Search for Model">
                                                <div id="modelResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="modelSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                                <small class="form-text text-muted">Can't find the Model your after?
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#newModel">
                                                        Click Here</a> to create one.</small>
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
                                                                    <x-form.input :name="$field->name"
                                                                                  value="{{old(str_replace(' ', '_', strtolower($field->name)))}}"
                                                                                  :label="false"/>
                                                                    @break
                                                                    @case('Textarea')
                                                                    <x-form.textarea :name="$field->name"
                                                                                     formAttributes=" cols=' 30' rows='10' "
                                                                                     value="{{old(str_replace(' ', '_', strtolower($field->name)))}}"/>
                                                                    @break

                                                                    @case('Select')

                                                                    <?php $array = explode("\r\n", $field->value);?>
                                                                    <label
                                                                        for="{{strtolower($field->name)}}">{{$field->name}}</label>
                                                                    <select type="text"
                                                                            class="form-control @if ($errors->has(str_replace(' ', '_', strtolower($field->name))))  {!! 'border border-danger' !!} @endif"
                                                                            name="{{str_replace(' ', '_', strtolower($field->name))}}">

                                                                        @foreach($array as $key => $value)
                                                                            <option
                                                                                value="{{ $value }}" @if(old(str_replace(' ', '_', strtolower($field->name))) == $value ){{'selected'}}@endif>
                                                                                {{ $value}}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                    @break
                                                                    @case('Checkbox')
                                                                    <?php $array = explode("\r\n", $field->value);?>
                                                                    <?php $values = explode(",", old(str_replace(' ', '_', strtolower($field->name))));?>
                                                                    <div class="form-check form-check-inline mr-2 p-2 ">
                                                                        <input class="form-check-input" type="checkbox"
                                                                               value="{{ $model->id }}"
                                                                               name="{!! strtolower($field->name) !!}[]">
                                                                        <label class="form-check-label "
                                                                               for="{!! strtolower($field->name) !!}">{{ $field->name }}</label>
                                                                    </div>
                                                                    @break
                                                                    @default
                                                                    <x-form.input :name="$field->name"
                                                                                  value="{{old(str_replace(' ', '_', strtolower($field->name)))}}"/>
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
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="modelInfo" class="bg-light p-4">
                                                <div class="model_title text-center h4 mb-3">Asset Model</div>
                                                <div class="model_image p-4">
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/device-image.svg') }}" width="100%"
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
                                <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel"
                                     aria-labelledby="purchase-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3">
                                            <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                            <div class="form-group">
                                                <x-form.input name="order_no" value="{{old('order_no')}}"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.date name="purchased_date" formAttributes=""
                                                             value="{{old('purchased_date')}}"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="purchased_cost" formAttributes=""
                                                              value="{{old('purchased_cost')}}"/>
                                                <div class="form-check mt-2 ml-1">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           @if(old('donated') == 1) checked @endifname="donated"
                                                           id="donated">
                                                    <label class="form-check-label" for="donated">
                                                        Donated
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="findSupplier">Supplier</label>
                                                <input type="hidden" id="supplier_id" name="supplier_id"
                                                       class="form-control mb-3" readonly
                                                       value="{{old('supplier_id')}}">
                                                <input class="form-control" type="text" name="find_supplier"
                                                       id="findSupplier" value="{{old('find_supplier')}}"
                                                       placeholder="Search for Supplier">
                                                <div id="supplierResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="supplierSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="warranty"/>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="supplierInfo" class="bg-light p-4">
                                                <div class="model_title text-center h4 mb-3">Supplier Name</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}"
                                                         height="150px" alt="Select Profile Picture">
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
                                                       class="form-control mb-3  " readonly
                                                       value="{{old('location_id')}}">
                                                <input
                                                    class="form-control @if ($errors->has("location_id"))  {!! 'border border-danger' !!} @endif"
                                                    type="text" name="find_location" id="findLocation"
                                                    value="{{old('find_location')}}" placeholder="Search for Location">
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
                                                <div class="model_title text-center h4 mb-3">Location Name</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/location-image.svg') }}"
                                                         height="200px" alt="Select Profile Picture">
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

                                            <div class="form-group">
                                                <x-form.date name="audit_date"/>
                                            </div>
                                            <div id="categories" class="form-control h-auto p-4 mb-3 bg-light">
                                                <h4 class="h6 mb-4 text-center">Categories</h4>
                                                <div class="position-relative">
                                                    @csrf
                                                    <input type="hidden" id="category_id" name="category"
                                                           class="form-control mb-3" readonly value="">
                                                    <input class="form-control" type="text" name="find_category"
                                                           id="findCategory" value=""
                                                           placeholder="Search for Categories">
                                                    <div id="categoryResults"
                                                         class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                         style="visibility: hidden; z-index: 2;">
                                                        <ul id="categorySelect">
                                                            <li>Nothing to Return</li>
                                                        </ul>
                                                    </div>
                                                    <div id="selectedCategories" class="row mt-3 mb-2 p-2">
                                                        @if($cats = old('category'))
                                                            <?php $categories = explode(',', $cats)?>
                                                            @foreach($categories as $id)
                                                                @php($category = \App\Models\Category::find($id))
                                                                <div
                                                                    class="border border-gray shadow bg-white p-2 rounded d-flex justify-content-between align-items-center">
                                                                    <span>{{$category->name}}</span>
                                                                    <i class="fas fa-times ml-4 text-danger pointer"
                                                                       data-name="{{$category->id}}"
                                                                       onclick="javascript:removeCategory(this);"></i>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <x-form.select name="status_id" :models="$statuses"
                                                               selected="{{old('status_id')}}"/>
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
    <x-models.create :mans="$mans" :depreciation="$depreciation" :fieldsets="$fieldsets"/>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/assets.js')}}"></script>
    <script>

        const newAssetBtn = document.querySelector('.add-asset');
        const assets = document.querySelector('.assets');
        let elements = assets.querySelectorAll('.form-group');

        function initTabbing() {
            assets.querySelectorAll('input').forEach(el => {
                el.addEventListener('keydown', e => {
                    if (e.keyCode === 13) {
                        let index = el.tabIndex;
                        let nextEl = assets.querySelector(`[tabindex='${index + 1}']`);
                        if (nextEl) {
                            nextEl.focus();
                        } else {
                            assets.querySelector(`[tabindex='1']`).focus();
                        }
                    }
                });
            });
        }


        const createNewBtn = document.querySelector('#createBtn');
        const createForm = document.querySelector('#createNewAsset');

        createNewBtn.addEventListener('click', function (e) {
            createForm.submit();
        })


        newAssetBtn.addEventListener('click', function (e) {

            if (elements.length >= 1) {
                element = elements[0].cloneNode(true);
                inputs = element.querySelectorAll('input');
                inputs.forEach((input) => {
                    input.value = "";
                })
                assets.appendChild(element);
                initAssetsFields();
                initTabIndex();
                initTabbing();
            }
        });

        function initTabIndex() {
            let inputs = assets.querySelectorAll('input');
            let i = 1;
            inputs.forEach((input) => {
                input.tabIndex = i;
                i++
            })
        }

        initTabIndex();
        initTabbing();

        function initAssetsFields() {
            elements = assets.querySelectorAll('.form-group');

            elements.forEach((item) => {
                item.addEventListener('click', function (e) {
                    if (e.target.classList.contains('asset-remove', 'fa-times')) {
                        removeItem(item);
                    }
                });
            });
        }

        function removeItem(item) {
            if (assets.querySelectorAll('.form-group').length > 1) {
                item.remove();
            }
        }


    </script>
@endsection
