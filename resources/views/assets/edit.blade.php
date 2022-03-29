@extends('layouts.app')

@section('title', 'Edit Asset')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('assets.update', $asset->id)">
        <x-wrappers.nav title="Edit New Asset(s)">
            @can('viewAll' ,\App\Models\Asset::class)
                <x-buttons.return :route="route('assets.index')"> Assets</x-buttons.return>
            @endcan

            <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm p-2 p-md-1"><i
                    class="fas fa-question fa-sm text-dark-50 mr-lg-1"></i><span
                    class="d-none d-lg-inline-block">Help</span></a>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Change or Update an Asset in the asset management system. Enter or change in the
                            following information and click the 'Save' button. Or click the 'Back' button to return the
                            Assets page. </p>
            <div class="row row-eq-height container m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            <x-form.errors/>

                            <ul id="tab-bar" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
                                       role="tab" aria-controls="home" aria-selected="true">Overview</a>
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
                                    <a class="nav-link" id="attributes-tab" data-bs-toggle="tab" href="#attributes"
                                       role="tab" aria-controls="home" aria-selected="true">Attributes</a>
                                </li>
                            </ul>
                            @csrf
                            @method('PATCH')
                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent">
                                <div class="tab-pane fade show p-2 pt-4 active" id="home" role="tabpanel"
                                     aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3">
                                            <div class="form-group position-relative">
                                                <label for="findModel">Asset Model</label>
                                                <input type="hidden" id="asset_model" name="asset_model"
                                                       class="form-control mb-3" value="{{ $asset->model->id ?? 0 }}"
                                                       readyonly>
                                                <input class="form-control" type="text" name="find_model" id="findModel"
                                                       value="{{ $asset->model->name ?? null }}" autocomplete="off"
                                                       placeholder="Search for Model">
                                                <div id="modelResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="modelSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                                <small class="form-text text-muted">Can't find the Model your
                                                                                    after?
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#newModel">Click
                                                                                                                  Here</a>to
                                                                                    create one.</small>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="name" formAttributes="required"
                                                              :value="$asset->name"/>
                                            </div>

                                            <div class="form-group">
                                                <x-form.input name="asset_tag" :value="$asset->asset_tag"/>
                                            </div>

                                            <div class="form-group">
                                                <x-form.input name="serial_no" formAttributes="required"
                                                              :value="$asset->serial_no"/>
                                            </div>
                                            @php
                                                if(old('model_id')){
                                                    $model_id = old('model_id');
                                                    $model = \App\Models\AssetModel::find($model_id);
                                                }else{
                                                    $model = $asset->model;
                                                }
                                            @endphp
                                            <div id="additional-fields"
                                                 @if($asset->model()->exists() && $model->fieldset_id == 0){{ 'style="display: none;"'}}@endif class="border border-gray p-2 mb-3">
                                                @if($asset->model()->exists() && $model->fieldset()->exists())
                                                    @php( $field_array = [])
                                                    @foreach($asset->fields as $as)
                                                        @php( $field_array[$as->id] = $as->pivot->value)
                                                    @endforeach

                                                    @foreach($model->fieldset->fields as $field)

                                                        <div class="form-group">
                                                            <label
                                                                for="{{str_replace(' ', '_', strtolower($field->name))}}">{{$field->name}}</label>
                                                            @switch($field->type)
                                                                @case('Text')
                                                                <x-form.input :name="$field->name"
                                                                              :value="old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id] ?? ''"/>
                                                                @break
                                                                @case('Textarea')
                                                                <x-form.input :name="$field->name"
                                                                              :value="old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id] ?? ''"/>
                                                                @break
                                                                @case('Select')
                                                                <?php
                                                                if(count($field_array) != 0)
                                                                {
                                                                    if(old(str_replace(' ', '_', strtolower($field->name))))
                                                                    {
                                                                        $vid = old(str_replace(' ', '_', strtolower($field->name)));
                                                                    } else
                                                                    {
                                                                        if(isset($field_array[$field->id]))
                                                                        {
                                                                            $vid = $field_array[$field->id];
                                                                        } else
                                                                        {
                                                                            $vid = 0;
                                                                        }

                                                                    }
                                                                } else
                                                                {
                                                                    $vid = 0;
                                                                }?>
                                                                <?php $array = explode("\r\n", $field->value);?>
                                                                <select
                                                                    name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                    class="form-control">
                                                                    @foreach($array as $id=>$key)
                                                                        <option
                                                                            value="{{ $key }}" @if($vid == $key){{ 'selected'}}@endif>{{ $key }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @break
                                                                @case('Checkbox')
                                                                <?php $array = explode("\r\n", $field->value);?>
                                                                <?php
                                                                if(old(str_replace(' ', '_', strtolower($field->name))))
                                                                {
                                                                    $values = old(str_replace(' ', '_', strtolower($field->name)));
                                                                } else
                                                                {
                                                                    $values = explode(",", $field_array[$field->id]);
                                                                }
                                                                ?>
                                                                @foreach($array as $id=>$key)
                                                                    <br><input type="checkbox"
                                                                               name="{{str_replace(' ', '_', strtolower($field->name))}}[]"
                                                                               value="{{ $key }}"
                                                                    @if(in_array($key, $values)){{ 'checked'}}@endif>
                                                                    <label>&nbsp;{{ $key }}</label>
                                                                @endforeach
                                                                @break
                                                                @default
                                                                <input type="text" class="form-control"
                                                                       name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                                                       placeholder="{{ $field->name }}"
                                                                       value="{{ old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id]}}">
                                                            @endswitch
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="modelInfo" class="bg-light p-4">
                                                <div
                                                    class="model_title text-center h4 mb-3">{{ $asset->model->name ?? 'Asset Model' }}</div>
                                                <div class="model_image p-4">
                                                    @if($asset->model()->exists() && $asset->model->photo()->exists())
                                                        @php($path = $asset->model->photo()->path ?? asset('images/svg/device-image.svg'))
                                                    @else
                                                        @php($path = asset('images/svg/device-image.svg'))
                                                    @endif
                                                    <img id="profileImage" src="{{ $path }}" height="150px"
                                                         alt="Select Profile Picture">
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Manufacturer: {{ $asset->model->manufacturer->name ?? 'No Manufacturer found'}}
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Model No: {{ $asset->model->model_no ?? 'N/A'}}
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    @if($asset->model->depreciation)
                                                        @php($months = $asset->model->depreciation->years * 12)
                                                        Deprecation: {{ $asset->model->depreciation->name}}
                                                        ({{$months}}
                                                        months)
                                                    @else
                                                        Deprecation:Null
                                                    @endif
                                                </div>
                                                <div class="model_no py-2 px-4">
                                                    Additional Fieldsets: {{ $asset->model->fieldset->name}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade  p-2 pt-4 " id="purchase" role="tabpanel"
                                     aria-labelledby="purchase-tab">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 p-4 mb-3">
                                            <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                            <div class="form-group">

                                                <x-form.input name="order_no" :value="$asset->order_no"/>
                                            </div>
                                            <div class="form-group">
                                                <?php if(old('purchased_date'))
                                                {
                                                    $date = old('purchased_date');
                                                } else
                                                {
                                                    $date = $asset->purchased_date;
                                                } ?>
                                                <x-form.date name="purchased_date" formAttributes="required"
                                                             :value="\Carbon\Carbon::parse($date)->format('Y-m-d')"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="purchased_cost" formAttributes="required"
                                                              :value="$asset->purchased_cost"/>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="donated" id="donated"
                                                           @if($asset->donated == 1) checked @endif>
                                                    <label class="form-check-label" for="donated">
                                                        Donated
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="findSupplier">Supplier</label>
                                                <input type="hidden" id="supplier_id" name="supplier_id"
                                                       value="{{ $asset->supplier->id ?? '' }}"
                                                       class="form-control mb-3" readyonly>
                                                <input class="form-control" type="text" name="find_supplier"
                                                       id="findSupplier" value="{{ $asset->supplier->name ?? ''}}"
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
                                                <x-form.input name="warranty" :value="$asset->warranty"/>
                                            </div>


                                        </div>

                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="supplierInfo" class="bg-light p-4">
                                                <div
                                                    class="model_title text-center h4 mb-3">{{ $asset->supplier->name ?? 'Supplier Name'}}</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    @if($asset->supplier()->exists() && $asset->supplier->photo()->exists())
                                                        @php($path = $asset->supplier->photo()->path ?? asset('images/svg/suppliers.svg'))
                                                    @else
                                                        @php($path = asset('images/svg/suppliers.svg'))
                                                    @endif
                                                    <img id="profileImage" src="{{ $path }}" height="150px"
                                                         alt="Select Profile Picture">
                                                </div>
                                                @if($asset->supplier()->exists() && $asset->supplier->address_1 != '')
                                                    <div class="model_no py-2 px-4 text-center">
                                                        Address
                                                    </div>
                                                @endif
                                                <div class="model_no py-2 px-4 text-center">
                                                    Website: {{ $asset->supplier->url ?? ''}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Email: {{ $asset->supplier->email ?? ''}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{ $asset->supplier->notes ?? 'Notes'}}
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
                                                       value="{{ $asset->location_id}}" class="form-control mb-3"
                                                       readyonly>
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="{{ $asset->location->name }}"
                                                       placeholder="Search for Supplier">
                                                <div id="locationResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="locationSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <x-form.input name="room" :value="$asset->room"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="locationInfo" class="bg-light p-4">
                                                <div
                                                    class="model_title text-center h4 mb-3">{{ $asset->location->name ?? 'Location Name' }}</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    @if($asset->location()->exists() && $asset->location->photo()->exists())
                                                        @php($path = $asset->location->photo()->path ?? asset('images/svg/location-image.svg'))
                                                    @else
                                                        @php($path = asset('images/svg/location-image.svg'))
                                                    @endif
                                                    <img id="profileImage" src="{{ $path }}" height="150px"
                                                         alt="Select Profile Picture">
                                                </div>
                                                @if($asset->location()->exists() && $asset->location->address_1 != '')
                                                    <div class="model_no py-2 px-4 text-center">
                                                        {{ $asset->location->address_1}}, {{$asset->location->city}}
                                                                                        , {{ $asset->location->postcode}}
                                                    </div>
                                                @endif
                                                <div class="model_no py-2 px-4 text-center">
                                                    Website: {{ $asset->location->url}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    Email: {{ $asset->location->email ?? ''}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{ $asset->location->notes ?? 'Notes'}}
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
                                                <x-form.date name="audit_date" :value="$asset->audit_date"/>
                                            </div>
                                            <div id="categories" class="form-control h-auto p-4 mb-3 bg-light">
                                                <h4 class="h6 mb-4 text-center">Categories</h4>
                                                <div class="position-relative">
                                                    <input type="hidden" id="category_id" name="category"
                                                           value="{{ implode(",",$asset->category->pluck('id')->toArray())}}"
                                                           class="form-control mb-3" readyonly>
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
                                                        @foreach($asset->category as $category)
                                                            <div id="cat{{$category->id}}" class="p-2 col-4">
                                                                <div
                                                                    class="border border-gray shadow bg-white p-2 rounded d-flex justify-content-between align-items-center">
                                                                    <span>{{$category->name}}</span>
                                                                    <i class="fas fa-times ml-4 text-danger pointer"
                                                                       data-name="{{$category->id}}"
                                                                       onclick="javascript:removeCategory(this);"></i>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <x-form.select name="status_id" :models="$statuses"
                                                               :selected="$asset->status_id"/>
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
@endsection
