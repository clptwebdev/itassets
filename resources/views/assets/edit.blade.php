@extends('layouts.app')

@section('title', 'Edit Asset')

@section('css')

@endsection

@section('content')
<form action="{{ route('assets.update', $asset->id)}}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Asset</h1>

        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-dark-50"></i> Back to Assets</a>
            <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                    class="fas fa-question fa-sm text-dark-50"></i> need Help?</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                    class="far fa-save fa-sm text-dark-50"></i> Save
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
                                    <label for="asset_tag">Asset Name</label>
                                    <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                                        id="name" placeholder="" value="{{ old('name') ?? $asset->name}}" required>
                                </div>

                                <div class="form-group">
                                    <label for="asset_tag">Asset Tag Number</label><span class="text-danger">*</span>
                                    <input type="text"
                                        class="form-control @if($errors->has('name')){{'border-danger'}}@endif"
                                        name="asset_tag" id="asset_tag" value="{{ old('asset_tag') ?? $asset->asset_tag}}">
                                </div>
                                <div class="form-group">
                                    <label for="serial_no">Serial Number</label><span class="text-danger">*</span>
                                    <input type="text"
                                        class="form-control @if($errors->has('serial_no')){{ 'border-danger'}}@endif"
                                        name="serial_no" id="serial_no" value="{{ old('serial_no') ?? $asset->serial_no }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="room">Location</label>
                                    <input type="text" class="form-control <?php if ($errors->has('room')) {?> border border-danger<?php }?>" 
                                        id="location" placeholder="" value="{{ $asset->location->name}}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="room">Room</label>
                                    <input type="text" class="form-control <?php if ($errors->has('room')) {?> border border-danger<?php }?>" name="room"
                                        id="room" placeholder="" value="{{ old('room') ?? $asset->room}}">
                                </div>

                                <div class="form-group">
                                    <label for="audit_date">Audit Date</label>
                                    @php if(old('audit_date')){ $date=old('audit_date');}else{$date= $asset->audit_date;} @endphp
                                    <input type="date"
                                        class="form-control <?php if ($errors->has('audit_date')) {?>border-danger<?php }?>"
                                        name="audit_date" id="audit_date" value="{{ \Carbon\Carbon::parse($date)->format('Y-m-d')}}">
                                </div>


                            </div>

                            <div class="col-12 col-sm-6 bg-light p-2 mb-3">
                                <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                <div class="form-group">
                                    <label for="order_no">Order No</label>
                                    <input type="text"
                                        class="form-control @if($errors->has('order_no')){{ 'border-danger'}}@endif"
                                        name="order_no" id="order_no" value="{{ old('order_no') ?? $asset->order_no }}">
                                </div>
                                <div class="form-group">
                                    <label for="purchased_date">Purchased Date</label>
                                    @php if(old('purchased_date')){ $date=old('purchased_date');}else{$date= $asset->purchased_date;} @endphp
                                    <input type="date"
                                        class="form-control @if($errors->has('purchased_date')){{'border-danger'}}@endif"
                                        name="purchased_date" id="purchased_date" value="{{ \Carbon\Carbon::parse($date)->format('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="purchased_cost">Purchased Cost</label>
                                    <input type="text"
                                        class="form-control @if($errors->has('purchased_cost')){{'border-danger'}}@endif"
                                        name="purchased_cost" id="purchased_cost" placeholder="£" value="{{ old('purchased_cost') ?? $asset->purchased_cost}}">
                                        <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="donated" id="donated" @if($asset->donated == 1) checked @endif>
                                        <label class="form-check-label" for="donated">
                                            Donated
                                        </label>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="purchased_cost">Supplier</label>
                                    @php if(old('supplier_id')){ $id=old('supplier_id');}else{ $id= $asset->supplier_id;} @endphp
                                    <select name="supplier_id"
                                        class="form-control <?php if ($errors->has('supplier')) {?>border-danger<?php }?>">
                                        <option value="0">No Supplier</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @if($asset->supplier_id == $supplier->id){{'selected'}}@endif>{{ $supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="purchased_date">Warranty (Months)</label>
                                    <input type="number"
                                        class="form-control @php if ($errors->has('warranty')){ echo 'border-danger';}@endphp"
                                        name="warranty" id="warranty" value="{{old('warranty') ?? $asset->warranty}}">
                                </div>

                                <div class="form-group">
                                    <label for="asset_model">Asset Model Select</label>
                                        <span class="text-danger">*</span>
                                        @php if(old('asset_model')){$id = old('asset_model');}else{ $id= $asset->model->id ?? 0;} @endphp
                                        <select type="dropdown" class="form-control" name="asset_model" id="asset_model"
                                            required onchange="getFields(this);" autocomplete="off">
                                            <option value="0">Please Select a Model</option>
                                            @foreach($models as $model)
                                            <option value="{{ $model->id }}" @if($id ==
                                            $model->id){{ 'selected'}}@endif>{{ $model->name }}</option>

                                            @endforeach
                                        </select>
                                </div>
                            </div>
                        </div>
                        @php
                            if(old('model_id')){
                                $model_id = old('model_id');
                                $model = \App\Models\AssetModel::find($model_id);
                            }else{
                                $model = $asset->model;
                            }
                        @endphp
                        <div id="additional-fields" @if($asset->model()->exists() && $model->fieldset_id == 0){{ 'style="display: none;"'}}@endif class="border border-secondary p-2 mb-3">
                            @if($asset->model()->exists() && $model->fieldset()->exists())
                            @php( $field_array = [])
                            @foreach($asset->fields as $as)
                            @php( $field_array[$as->id] = $as->pivot->value)
                            @endforeach

                            @foreach($model->fieldset->fields as $field)

                            <div class="form-group">
                                <label for="{{str_replace(' ', '_', strtolower($field->name))}}">{{$field->name}}</label>
                                @switch($field->type)
                                @case('Text')
                                <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                    placeholder="{{ $field->name }}" value="{{ old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id] ?? ''}}">
                                @break
                                @case('Textarea')
                                <textarea name="{{ str_replace(' ', '_', strtolower($field->name))}}"cols="30" rows="10"
                                    class="form-contol">{{ old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id] ?? ''}}</textarea>
                                @break
                                @case('Select')
                                <?php
                                    if(count($field_array) != 0){
                                        if(old(str_replace(' ', '_', strtolower($field->name)))){
                                            $vid = old(str_replace(' ', '_', strtolower($field->name)));
                                        }else{
                                            if(isset($field_array[$field->id])){
                                                $vid = $field_array[$field->id];
                                            }else{
                                                $vid = 0;
                                            }

                                        }
                                    }else{ $vid = 0;}?>
                                <?php $array = explode("\r\n", $field->value);?>
                                <select name="{{str_replace(' ', '_', strtolower($field->name))}}" class="form-control">
                                    @foreach($array as $id=>$key)
                                    <option value="{{ $key }}" @if($vid == $key){{ 'selected'}}@endif>{{ $key }}</option>
                                    @endforeach
                                </select>
                                @break
                                @case('Checkbox')
                                <?php $array = explode("\r\n", $field->value);?>
                                <?php
                                    if(old(str_replace(' ', '_', strtolower($field->name)))){
                                        $values = old(str_replace(' ', '_', strtolower($field->name)));
                                    }else{
                                        $values = explode(",", $field_array[$field->id]);
                                    }
                                ?>
                                @foreach($array as $id=>$key)
                                <br><input type="checkbox" name="{{str_replace(' ', '_', strtolower($field->name))}}[]" value="{{ $key }}"
                                @if(in_array($key, $values)){{ 'checked'}}@endif>
                                <label>&nbsp;{{ $key }}</label>
                                @endforeach
                                @break
                                @default
                                <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}"
                                    placeholder="{{ $field->name }}" value="{{ old(str_replace(' ', '_', strtolower($field->name))) ?? $field_array[$field->id]}}">
                                @endswitch
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <div id="categories" class="form-control h-auto p-4 mb-3">
                            @php($array = $asset->category->pluck('id')->toArray())
                            <h4 class="h6 mb-4 text-center">Categories</h4>
                            @foreach($categories as $category)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="{{ $category->id }}" name="category[]" id="category{{$category->id}}"
                                @if(in_array($category->id, $array)){{ 'checked'}}@endif
                                >
                                <label class="form-check-label" for="category{{$category->id}}">{{ $category->name }}</label>
                            </div>
                            @endforeach
                        </div>

                        <div class="form-row">
                            <label for="status">Current Status</label><span class="text-danger">*</span>
                            <select type="text" class="form-control mb-3 <?php if ($errors->has('status')) {?>border-danger<?php }?>"
                                name="status_id" id="status_id" value="Stored">
                                <?php $status_id = old('status_id') ?? $asset->status_id;?>
                                <option value="0" @if($status_id==0){{'selected'}}@endif>Unset</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status->id }}" @if($status_id==$status->id){{'selected'}}@endif>{{ $status->name}}
                                </option>
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
    <script type="text/javascript">
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
