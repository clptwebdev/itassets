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

                            @csrf

                            <div class="form-row">
                                <div class="col-12 col-sm-6 p-2 mb-3">
                                    <h3 class="h6 text-center mb-3">Device Information</h3>

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

                                    <div class="form-group">
                                        <label for="School Location">School Location</label><span
                                            class="text-danger">*</span>
                                        <select type="text"
                                                class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border border-danger<?php }?>"
                                                name="location_id" id="location_id">
                                            <option value="0" @if(old('location_id') == 0){{'selected'}}@endif>
                                                Unallocated
                                            </option>
                                            @foreach($locations as $location)
                                                <option
                                                    value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="room">Room</label>
                                        <input type="text"
                                               class="form-control <?php if ($errors->has('room')) {?> border border-danger<?php }?>"
                                               name="room"
                                               id="room" placeholder="" value="{{ old('room')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="audit_date">Audit Date</label>
                                        <input type="date"
                                               class="form-control <?php if ($errors->has('audit_date')) {?>border border-danger<?php }?>"
                                               name="audit_date" id="audit_date"
                                               value="{{ old('audit_date') ?? \Carbon\Carbon::now()->addYear()->format('Y-m-d')}}">
                                    </div>


                                </div>

                                <div class="col-12 col-sm-6 bg-light p-2 mb-3">
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
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_cost">Supplier</label>
                                        <select name="supplier_id"
                                                class="form-control <?php if ($errors->has('supplier')) {?>border border-danger<?php }?>">
                                            <option value="0" @if(old('supplier_id') == 0){{'selected'}}@endif>No
                                                Supplier
                                            </option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id){{'selected'}}@endif>{{ $supplier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_date">Warranty (Months)</label>
                                        <input type="number"
                                               class="form-control <?php if ($errors->has('warranty')) {?>border border-danger<?php }?>"
                                               name="warranty" id="warranty" value="{{ old('warranty') ?? 24}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="asset_model">Asset Model Select</label><span
                                            class="text-danger">*</span>
                                        <select type="dropdown" class="form-control" name="asset_model" id="asset_model"
                                                onchange="getFields(this);" autocomplete="off" required>
                                            <option value="0" @if(old('asset_model') == 0){{'selected'}}@endif>Please
                                                Select a Model
                                            </option>
                                            @foreach($models as $model)
                                                <option
                                                    value="{{ $model->id }}" @if(old('asset_model') == $model->id){{'selected'}}@endif>{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            @if(old('asset_model') !== null && $model = \App\Models\AssetModel::find(old('asset_model')))
                                <div id="additional-fields" class="border border-secondary p-2 mb-3">

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

                                </div>
                            @else
                                <div id="additional-fields" style="display: none;"
                                     class="border border-secondary p-2 mb-3">
                                    <span class="text-warning">No Additional Fields Required</span>
                                </div>
                            @endif

                            <div id="categories" class="form-control mh-100 p-4 mb-3">
                                <h4 class="h6 mb-4 text-center">Categories</h4>
                                @foreach($categories as $category)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                               name="category[]" id="category{{$category->id}}">
                                        <label class="form-check-label"
                                               for="category{{$category->id}}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
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
        </section>
    </form>

@endsection

@section('modals')

@endsection

@section('js')
    <script>
        function getFields(obj) {
            $.ajax({
                url: `/assets/${obj.value}/model`,
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
    </script>
@endsection
