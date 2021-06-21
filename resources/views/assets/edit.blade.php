@extends('layouts.app')

@section('css')

@endsection

@section('content')
<form action="{{ route('assets.update', $asset->id)}}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Asset</h1>

        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Assets</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save
            </button>
        </div>
    </div>

    <section>
        <p class="mb-4">Change or Update an Asset in the asset management system. Enter or change in the following
            information and click
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
                        @method('PATCH')

                        <div class="form-row">
                            <div class="col-12 col-sm-6 p-2 mb-3">
                                <h3 class="h6 text-center mb-3">Device Information</h3>

                                <div class="form-group">
                                    <label for="asset_tag">Asset Tag Number</label><span class="text-danger">*</span>
                                    <input type="text"
                                        class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                        name="asset_tag" id="asset_tag" value="{{ $asset->asset_tag}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="serial_no">Serial Number</label><span class="text-danger">*</span>
                                    <input type="text"
                                        class="form-control <?php if ($errors->has('serial_no')) {?>border-danger<?php }?>"
                                        name="serial_no" id="serial_no" value="{{ $asset->serial_no }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="School Location">School Location</label><span
                                        class="text-danger">*</span>
                                    <select type="text"
                                        class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                        name="location_id" id="location_id" required>
                                        <option value="0" selected>No Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}" @if($asset->location->id == $location->id){{ 'selected'}}@endif>{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="audit_date">Audit Date</label>
                                    <input type="date"
                                        class="form-control <?php if ($errors->has('audit_date')) {?>border-danger<?php }?>"
                                        name="audit_date" id="audit_date" value="{{ \Carbon\Carbon::parse($asset->audit_date)->format('Y-m-d')}}">
                                </div>

                                <div class="form-group">
                                    <label for="asset_model">Asset Model Select</label><span
                                        class="text-danger">*</span>
                                    <select type="dropdown" class="form-control" name="asset_model" id="asset_model"
                                        required onchange="getFields(this);" autocomplete="off">
                                        <option value="0">Please Select a Model</option>
                                        @foreach($models as $model)
                                        <option value="{{ $model->id }}" @if($asset->model->id ==
                                        $model->id){{ 'selected'}}@endif>{{ $model->name }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 bg-light p-2 mb-3">
                                <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                <div class="form-group">
                                    <label for="order_no">Order No</label>
                                    <input type="text"
                                        class="form-control <?php if ($errors->has('order_no')) {?>border-danger<?php }?>"
                                        name="order_no" id="order_no" value="{{ $asset->order_no }}">

                                </div>
                                <div class="form-group">
                                    <label for="purchased_date">Purchased Date</label>
                                    <input type="date"
                                        class="form-control <?php if ($errors->has('purchased_date')) {?>border-danger<?php }?>"
                                        name="purchased_date" id="purchased_date" value="{{ \Carbon\Carbon::parse($asset->purchased_date)->format('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="purchased_cost">Purchased Cost</label>
                                    <input type="text"
                                        class="form-control <?php if ($errors->has('purchased_cost')) {?>border-danger<?php }?>"
                                        name="purchased_cost" id="purchased_cost" placeholder="£" value="{{ $asset->purchased_cost}}">
                                </div>
                                <div class="form-group">
                                    <label for="purchased_cost">Supplier</label>
                                    <select name="supplier_id"
                                        class="form-control <?php if ($errors->has('supplier')) {?>border-danger<?php }?>">
                                        <option value="0">No Supplier</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @if($asset->supplier->id == $supplier->id){{'selected'}}@endif>{{ $supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="purchased_date">Warranty (Months)</label>
                                    <input type="number"
                                        class="form-control <?php if ($errors->has('warranty')) {?>border-danger<?php }?>"
                                        name="warranty" id="warranty" value="{{$asset->warranty}}">

                                </div>
                            </div>
                        </div>
                        <div id="additional-fields" @if($asset->model->fieldset_id == 0){{ 'style="display: none;"'}}@endif class="border border-secondary p-2 mb-3">
                            @php( $field_array = [])
                            @foreach($asset->fields as $as)
                            @php( $field_array[$as->id] = $as->pivot->value)
                            @endforeach


                            @foreach($asset->model->fieldset->fields as $field)
                            
                            <div class="form-group">
                                <label for="{{str_replace(' ', '_', strtolower($field->name))}}">{{$field->name}}</label>
                                @switch($field->type)
                                @case('Text')
                                <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                    placeholder="{{ $field->pivot->name }}" value="{{ $field_array[$field->id]}}">
                                @break
                                @case('Textarea')
                                <textarea name="{{str_replace(' ', '_', strtolower($field->name))}}" id="" cols="30" rows="10"
                                    class="form-contol">{{ $field_array[$field->id]}}</textarea>
                                @break
                                @case('Select')
                                <?php $array = explode("\r\n", $field->value);?>
                                <select name="{{str_replace(' ', '_', strtolower($field->name))}}" class="form-control">
                                    @foreach($array as $id=>$key)
                                    <option value="{{ $key }}" @if($field_array[$field->id] == $key){{ 'selected'}}@endif>{{ $key }}</option>
                                    @endforeach
                                </select>
                                @break
                                @case('Checkbox')
                                <?php $array = explode("\r\n", $field->value);?>
                                <?php $values = explode(",", $field_array[$field->id]);?>
                                @foreach($array as $id=>$key)
                                <br><input type="checkbox" name="{{str_replace(' ', '_', strtolower($field->name))}}[]" value="{{ $key }}" 
                                @if(in_array($key, $values)){{ 'checked'}}@endif>
                                <label>&nbsp;{{ $key }}</label>
                                @endforeach
                                @break
                                @default
                                <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                    placeholder="{{ $field->name }}">
                                @endswitch
                            </div>
                            @endforeach
                        </div>
                        <div class="form-row">
                            <label for="status">Current Status</label><span class="text-danger">*</span>
                            <select type="text"
                                class="form-control mb-3 <?php if ($errors->has('status')) {?>border-danger<?php }?>"
                                name="status" id="status" value="Stored" required>
                                <option value="0">Stored</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection

    @section('modals')

    @endsection

    @section('js')
    <script>
        function getFields(obj){
                $.ajax({
                    url: `/assets/${obj.value}/model`,
                    success: function(data) {
                        document.getElementById("additional-fields").innerHTML = data;
                        document.getElementById("additional-fields").style.display = "block";
                    },
                    error: function(){
                        document.getElementById("additional-fields").innerHTML = "";
                        document.getElementById("additional-fields").style.display = "none";
                    },
                });
            }
    </script>
    @endsection